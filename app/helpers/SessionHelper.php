<?php
class SessionHelper
{
    //static means you can call it without creating an object
    public static function startSecureSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            $cookieParams = session_get_cookie_params();
            session_set_cookie_params([
                'lifetime' => $cookieParams['lifetime'],
                'path'     => $cookieParams['path'],
                'domain'   => $cookieParams['domain'],
                'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
                'httponly' => true,
                'samesite' => 'Strict',
            ]);
            session_start();
        }
    }

    // --- CSRF: create token if missing and return it
    public static function getCsrfToken(): string
    {
        self::startSecureSession();// ensures a secure session is active before touching $_SESSION.
        if (empty($_SESSION['csrf_token'])) {
            // 32 bytes -> 64 hex chars
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            // optional: token timestamp for expiry
            $_SESSION['csrf_token_time'] = time();
        }
        return $_SESSION['csrf_token'];
    }

    // Include an HTML hidden input
    public static function csrfInput(): string
    {
        $token = self::getCsrfToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    // Validate token provided by client. Optional expiry check.
    public static function validateCsrfToken(?string $token): bool
    {
        self::startSecureSession();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        //prevents timing attacks
        $valid = hash_equals($_SESSION['csrf_token'], $token);

        // Optional: expire tokens after e.g. 30 minutes
        /*
        $maxSeconds = 30 * 60;
        if (!empty($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time'] > $maxSeconds)) {
            // token expired
            $valid = false;
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
        */

        return $valid;
    }

    // Optional helper to invalidate current token (after successful POST)
    public static function clearCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION['csrf_token'], $_SESSION['csrf_token_time']);
    }
}
