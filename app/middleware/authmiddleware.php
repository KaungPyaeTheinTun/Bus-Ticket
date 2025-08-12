<?php

require_once APPROOT . '/helpers/SessionHelper.php';

class AuthMiddleware
{
    public static function requireRole($allowedRoles = null)
    {
        SessionHelper::startSecureSession();

        if (!isset($_SESSION['session_loginuserid'])) {
            $_SESSION['error'] = "Please login first.";
            header("Location: " . URLROOT . "/pages/login");
            exit();
        }

        if ($allowedRoles !== null) {
            $allowedRoles = (array)$allowedRoles;
            if (!in_array($_SESSION['role_id'], $allowedRoles)) {
                header("Location: " . URLROOT . "/pages/unauthorized");
                exit();
            }
        }
    }
}

