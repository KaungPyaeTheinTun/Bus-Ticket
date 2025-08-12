<?php
// tests/bootstrap.php
// adjust APPROOT to your structure if needed
define('APPROOT', realpath(__DIR__ . '/../app')); // edit if app path differs

// autoload vendor if you use composer for other libs
require_once __DIR__ . '/../vendor/autoload.php';

// ensure session available in tests
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Provide a safe redirect() implementation for tests (so the app's redirect helper doesn't call header+exit)
if (!function_exists('redirect')) {
    class RedirectException extends \Exception
    {
        public $path;
        public function __construct($path)
        {
            parent::__construct("REDIRECT:$path");
            $this->path = $path;
        }
    }

    function redirect($path)
    {
        // throw instead of sending header+exit so tests can assert redirects
        throw new RedirectException($path);
    }
}
