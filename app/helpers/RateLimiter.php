<?php

class RateLimiter
{
    public static function check(string $action, int $limit = 2, int $windowSeconds = 60)
    {
        if (session_status() == PHP_SESSION_NONE) 
        {
            session_start();
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];//Grabs the client IP address as seen by the web server.
        $key = "rate_{$action}_{$ip}";//e.g. rate_login_203.0.113.5

        //count: how many times this action has been called in the current window.
        //start: the Unix timestamp when the current window began.
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'start' => time()];
        }

        $data = &$_SESSION[$key];

        // Reset if window expired
        if (time() - $data['start'] > $windowSeconds) {
            $data['count'] = 0;
            $data['start'] = time();
        }

        $data['count']++;
 
        if ($data['count'] > $limit) {
            return false; // rate limit exceeded
        }

        return true; // allowed
    }
}
