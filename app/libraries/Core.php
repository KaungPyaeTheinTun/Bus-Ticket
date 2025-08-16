<?php

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    // Cache of instantiated dependencies
    protected $container = [];

    // Map class names (or keywords) to their folders
    protected $classMap = [
        'Controller'           => '../app/controllers/',
        'Service'              => '../app/services/',
        'Repository'           => '../app/repositories/',
        'Interface'            => '../app/interfaces/',
        'Helper'               => '../app/helpers/',
        'Library'              => '../app/libraries/',
        'DBconnection'         => '../app/config/',
        // Add more mappings if needed
    ];

    public function __construct()
    {
        $url = $this->getURL();

        // Controller detection
        if (isset($url[0])) {
            $controllerName = ucwords($url[0]);
            if (file_exists('../app/controllers/' . $controllerName . '.php')) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        // Require controller file
        require_once('../app/controllers/' . $this->currentController . '.php');

        // Instantiate controller with dependencies injected
        $this->currentController = $this->resolveController($this->currentController);

        // Method detection
        if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        }

        // Parameters for the method
        $this->params = $url ? array_values($url) : [];

        // Call the controller method with params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    protected function resolveController(string $controllerName)
    {
        $reflection = new ReflectionClass($controllerName);
        $constructor = $reflection->getConstructor();

        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            return new $controllerName;
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $depClass = $param->getType() && !$param->getType()->isBuiltin()
                ? $param->getType()->getName()
                : null;

            if ($depClass) {
                if (!isset($this->container[$depClass])) {
                    $this->container[$depClass] = $this->resolveDependency($depClass);
                }
                $dependencies[] = $this->container[$depClass];
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency '{$param->getName()}' for controller '{$controllerName}'");
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    protected function resolveDependency(string $className)
    {
        $filePath = $this->getFilePathForClass($className);
        if (!$filePath || !file_exists($filePath)) {
            throw new Exception("Class file for '{$className}' not found at '{$filePath}'");
        }

        require_once $filePath;

        $reflection = new ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            return new $className;
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $depClass = $param->getType() && !$param->getType()->isBuiltin()
                ? $param->getType()->getName()
                : null;

            if ($depClass) {
                if (!isset($this->container[$depClass])) {
                    $this->container[$depClass] = $this->resolveDependency($depClass);
                }
                $dependencies[] = $this->container[$depClass];
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency '{$param->getName()}' for class '{$className}'");
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    protected function getFilePathForClass(string $className): ?string
    {
        // Find mapping by matching any keyword in class name
        foreach ($this->classMap as $keyword => $folder) {
            if (stripos($className, $keyword) !== false) {
                return $folder . $className . '.php';
            }
        }

        // Default to app root folder (change if needed)
        $defaultPath = '../app/' . $className . '.php';
        return file_exists($defaultPath) ? $defaultPath : null;
    }

    public function getURL(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}
