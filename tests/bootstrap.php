<?php
// tests/bootstrap.php

// Define APPROOT for consistent absolute paths in tests
if (!defined('APPROOT')) {
    define('APPROOT', realpath(__DIR__ . '/../app'));
}

// Load Composer's autoloader if available
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

// Start a session if not already started (some classes may rely on sessions)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Provide a safe redirect() helper for tests to avoid headers + exit()
// Instead, throw an exception to allow testing redirections
if (!function_exists('redirect')) {

    class RedirectException extends \Exception
    {
        public string $path;

        public function __construct(string $path)
        {
            parent::__construct("REDIRECT:$path");
            $this->path = $path;
        }
    }

    /**
     * Simulates redirect by throwing an exception with target path.
     *
     * @param string $path Target path for redirection.
     * @throws RedirectException
     */
    function redirect(string $path): void
    {
        throw new RedirectException($path);
    }
}
