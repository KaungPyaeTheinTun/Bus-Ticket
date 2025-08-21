<?php
class SessionManager
{
    private $regenInterval = 180; // Regenerate ID every 5 min
    private $timeout       = 600; // Inactivity timeout (30 min)

   public function __construct()
    {
        // Set ini configs only if session has NOT started yet
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', 1);
            ini_set('session.cookie_secure', 1); // only works if HTTPS
            ini_set('session.use_strict_mode', 1);

            session_start();
        }

        $this->validateSession();
    }

    private function validateSession()
    {
        // If new session, set markers
        if (!isset($_SESSION['ip']) || !isset($_SESSION['ua']) || !isset($_SESSION['created'])) {
            $_SESSION['ip']      = $_SERVER['REMOTE_ADDR'];
            $_SESSION['ua']      = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['created'] = time();
            $_SESSION['last_activity'] = time();
        } else {
            // 🚨 Prevent Session Hijacking
            if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR'] ||
                $_SESSION['ua'] !== $_SERVER['HTTP_USER_AGENT']) {
                $this->destroy("⚠️ Session Hijacking Detected!");
            }

            // 🚨 Prevent Inactivity abuse
            if (time() - $_SESSION['last_activity'] > $this->timeout) {
                $this->destroy("⚠️ Session Expired, Please login again !");
            }

            $_SESSION['last_activity'] = time();

            // 🚨 Prevent Session Fixation
            if (time() - $_SESSION['created'] > $this->regenInterval) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    public function destroy($message)
    {
        session_unset();
        session_destroy();
        if ($message) {
            setMessage('error', $message); 
        }
        redirect('pages/login');
        exit;
    }
}