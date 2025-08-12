<?php

require_once APPROOT . '/services/AuthService.php';

require_once APPROOT . '/helpers/UserValidator.php';

require_once APPROOT . '/helpers/SessionHelper.php';

class Auth extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    private function ensurePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('pages/login'); 
            exit;
        }
    }

    private function startSessionAndValidateCsrf()
    {
        SessionHelper::startSecureSession();
        if (!SessionHelper::validateCsrfToken($_POST['csrf_token'] ?? null)) {
            setMessage('error', 'Invalid request (CSRF).');
            redirect($_SERVER['HTTP_REFERER'] ?? 'pages/login');
            exit;
        }
    }

    private function validatePasswordMatch($password, $confirmPassword)
    {
        if (empty($password) || empty($confirmPassword)) {
            setMessage('error', '⚠️ All fields are required.');
            return false;
        }
        if ($password !== $confirmPassword) {
            setMessage('error', '⚠️ Passwords do not match.');
            return false;
        }
        return true;
    }

    public function register()
    {
        $this->ensurePost();
        $this->startSessionAndValidateCsrf();

        $validator = new UserValidator($_POST);
        $errors = $validator->validateForm();
        if ($errors) return $this->view('pages/register', $errors);

        $result = $this->authService->registerUser($_POST);
        if (!empty($result['error'])) { //Single error message string
            setMessage('error', $result['error']);
            redirect('pages/register');
            return;
        }
        if (!empty($result['errors'])) { //	Array of validation errors
            return $this->view('pages/register', $result['errors']);
        }

        setMessage('success', '✅ Successfully registered.');
        redirect('pages/login');
    }

    public function adminRegister()
    {
        $this->ensurePost();
        $this->startSessionAndValidateCsrf();

        $validator = new UserValidator($_POST);
        $errors = $validator->validateForm();
        if ($errors) return $this->view('backend/addadmin', $errors);

        $result = $this->authService->registerUser($_POST, ROLE_ADMIN);
        if (!empty($result['error'])) {
            setMessage('error', $result['error']);
            redirect('user/addadmin');
            return;
        }
        if (!empty($result['errors'])) {
            return $this->view('backend/addadmin', $result['errors']);
        }

        setMessage('success', '✅ Admin registered successfully.');
        redirect('user/profile');
    }

    public function login()
    {
        $this->ensurePost();
        $this->startSessionAndValidateCsrf();

        if (empty($_POST['email']) || empty($_POST['password'])) {
            setMessage('error', 'Email and Password are required.');
            redirect('pages/login');
            return;
        }

        $user = $this->authService->login($_POST['email'], $_POST['password']);
        if ($user) {
            SessionHelper::startSecureSession();
            session_regenerate_id(true);
            $_SESSION['session_loginuserid'] = $user['id'];
            $_SESSION['session_loginemail']  = $user['email'];
            $_SESSION['role_id']             = $user['role_id'];

            redirect($user['role_id'] == ROLE_ADMIN ? 'pages/dashboard' : 'pages/index');
        } else {
            setMessage('error', '⚠️ Fail to login!');
            redirect('pages/login');
        }
    }

    public function forgetpassword()
    {
        $this->ensurePost();

        if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            setMessage('error', '⚠️ Valid email is required.');
            redirect('pages/forgetpassword');
            return;
        }

        if ($this->authService->sendOTP($_POST['email'])) {
            session_start();
            $_SESSION['post_mail'] = $_POST['email'];
            redirect('pages/otp');
        } else {
            setMessage('error', '⚠️ Email not found!');
            redirect('pages/forgetpassword');
        }
    }

    public function otp()
    {
        $this->ensurePost();

        $otp = implode('', $_POST['otp'] ?? []);
        if (!preg_match('/^\d{6}$/', $otp)) {
            setMessage('error', '⚠️ Invalid OTP format.');
            redirect('pages/otp');
            return;
        }

        session_start();
        $email = $_SESSION['post_mail'] ?? null;
        if (!$email) {
            setMessage('error', '⚠️ Session expired. Please try again.');
            redirect('pages/forgetpassword');
            return;
        }

        if ($this->authService->verifyOTP($email, $otp)) {
            $_SESSION['otp'] = $email;
            redirect('pages/changepassword');
        } else {
            setMessage('error', '⚠️ Code is Incorrect');
            redirect('pages/otp');
        }
    }

    public function changepassword()
    {
        $this->ensurePost();

        session_start();
        $email = $_SESSION['otp'] ?? null;
        if (!$email) {
            setMessage('error', '⚠️ Session expired. Please try again.');
            redirect('pages/changepassword');
            return;
        }

        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!$this->validatePasswordMatch($password, $confirmPassword)) {
            redirect('pages/changepassword');
            return;
        }

        $validator = new UserValidator(['password' => $password]);
        if ($validator->validatePasswordOnly()) {
            setMessage('error', '⚠️ Password does not meet the required format.');
            redirect('pages/changepassword');
            return;
        }

        if ($this->authService->changePassword($email, $password)) {
            setMessage('success', '✅ Password changed successfully.');
            redirect('pages/login');
        } else {
            setMessage('error', '⚠️ Failed to update password.');
            redirect('pages/changepassword');
        }
    }

    public function changepasswordadmin()
    {
        $this->ensurePost();

        session_start();
        $adminId = $_SESSION['session_loginuserid'] ?? null;
        if (!$adminId) {
            setMessage('error', '⚠️ User not logged in.');
            redirect('/user/profile');
            return;
        }

        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm-password'] ?? '';

        if (!$this->validatePasswordMatch($password, $confirmPassword)) {
            redirect('/user/profile');
            return;
        }

        $validator = new UserValidator(['password' => $password]);
        if ($validator->validatePasswordOnly()) {
            setMessage('error', '⚠️ Password does not meet the required format.');
            redirect('/user/profile');
            return;
        }

        $passwordEncoded = base64_encode($password);
        $success = $this->authService->changePasswordById($adminId, $passwordEncoded);

        setMessage($success ? 'success' : 'error', $success ? '✅ Password changed successfully.' : '⚠️ Failed to change password.');
        redirect('/user/profile');
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        redirect('pages/login');
    }
}
