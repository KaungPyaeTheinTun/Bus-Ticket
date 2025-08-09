<?php

require_once APPROOT . '/services/AuthService.php';

require_once APPROOT . '/helpers/UserValidator.php';

class Auth extends Controller
{
    private $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new UserValidator($_POST);
            $errors = $validator->validateForm();

            if (!empty($errors)) {
                $this->view('pages/register', $errors);
                return;
            }

            $result = $this->authService->registerUser($_POST);

            if (!empty($result['error'])) {
                setMessage('error', $result['error']);
                redirect('pages/register');
            }

            if (!empty($result['errors'])) {
                $this->view('pages/register', $result['errors']);
                return;
            }

            setMessage('success', '✅ Successfully registered.');
            redirect('pages/login');
        }
    }

    public function adminRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new UserValidator($_POST);
            $errors = $validator->validateForm();

            if (!empty($errors)) {
                $this->view('backend/addadmin', $errors);
                return;
            }

            $result = $this->authService->registerUser($_POST, ROLE_ADMIN);

            if (!empty($result['error'])) {
                setMessage('error', $result['error']);
                redirect('user/addadmin');
            }

            if (!empty($result['errors'])) {
                $this->view('backend/addadmin', $result['errors']);
                return;
            }

            setMessage('success', '✅ Admin registered successfully.');
            redirect('user/profile');
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Simple inline validation here 
            if (empty($_POST['email']) || empty($_POST['password'])) {
                setMessage('error', 'Email and Password are required.');
                redirect('pages/login');
                return;
            }

            $user = $this->authService->login($_POST['email'], $_POST['password']);

            if ($user) {
                session_start();
                $_SESSION['session_loginuserid'] = $user['id'];
                $_SESSION['session_loginemail']  = $user['email'];
                $_SESSION['role_id']             = $user['role_id'];

                redirect($user['role_id'] == ROLE_ADMIN ? 'pages/dashboard' : 'pages/index');
            } else {
                setMessage('error', '⚠️ Fail to login!');
                redirect('pages/login');
            }
        }
    }

    public function forgetpassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    }

    public function otp()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    }

    public function changepassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $email = $_SESSION['otp'] ?? null;

            if (!$email) {
                setMessage('error', '⚠️ Session expired. Please try again.');
                redirect('pages/changepassword');
                return;
            }

            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($password) || empty($confirmPassword)) {
                setMessage('error', '⚠️ All fields are required.');
                redirect('pages/changepassword');
                return;
            }

            if ($password !== $confirmPassword) {
                setMessage('error', '⚠️ Passwords do not match.');
                redirect('pages/changepassword');
                return;
            }

            // Use UserValidator to validate password format
            $validator = new UserValidator(['password' => $password]);
            $pwErrors = $validator->validatePasswordOnly();

            if (!empty($pwErrors)) {
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
    }

    public function changepasswordadmin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $adminId = $_SESSION['session_loginuserid'] ?? null;

            if (!$adminId) {
                setMessage('error', '⚠️ User not logged in.');
                redirect('/user/profile');
                return;
            }

            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm-password'] ?? '';

            if (empty($password) || empty($confirmPassword)) {
                setMessage('error', '⚠️ All fields are required.');
                redirect('/user/profile');
                return;
            }

            if ($password !== $confirmPassword) {
                setMessage('error', '⚠️ Passwords do not match.');
                redirect('/user/profile');
                return;
            }

            // Use UserValidator to validate password format
            $validator = new UserValidator(['password' => $password]);
            $pwErrors = $validator->validatePasswordOnly();

            if (!empty($pwErrors)) {
                setMessage('error', '⚠️ Password does not meet the required format.');
                redirect('/user/profile');
                return;
            }

            $passwordEncoded = base64_encode($password);

            $success = $this->authService->changePasswordById($adminId, $passwordEncoded);

            setMessage($success ? 'success' : 'error', $success ? '✅ Password changed successfully.' : '⚠️ Failed to change password.');

            redirect('/user/profile');
        } else {
            redirect('/user/profile');
        }
    }
}
