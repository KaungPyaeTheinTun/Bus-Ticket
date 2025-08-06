<?php

class AuthMiddleware
{
    public static function adminOnly()
    {
        session_start();

        if (!defined('admin')) define('admin', 1);
        if (!isset($_SESSION['session_loginuserid']) || $_SESSION['role_id'] != admin) {
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }

    public static function userOnly()
    {
        session_start();

        if (!defined('user')) define('user', 2);
        // var_dump($_SESSION['role_id']);exit;
        if (!isset($_SESSION['session_loginuserid']) || $_SESSION['role_id'] != user) {
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }
    }
}