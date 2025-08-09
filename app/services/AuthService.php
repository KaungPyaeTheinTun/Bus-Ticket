<?php

require_once APPROOT . '/repositories/AuthRepository.php';

class AuthService
{
    private $repo;

    public function __construct(AuthRepositoryInterface $repo = null)
    {
        $this->repo = $repo ?: new AuthRepository();
    }

    public function registerUser(array $formData, int $roleId = ROLE_USER)
    {
        if ($this->repo->findByEmail($formData['email'])) {
            return ['error' => 'This email is already registered!'];
        }

        $validator = new UserValidator($formData);
        $errors = $validator->validateForm();

        if (!empty($errors)) {
            return ['errors' => $errors];
        }

        $user = [
            'name'          => $formData['name'],
            'phone'         => $formData['phone'],
            'email'         => $formData['email'],
            'password'      => base64_encode($formData['password']), // ❗ Ideally password_hash
            'profile_image' => 'default_profile.jpg',
            'token'         => bin2hex(random_bytes(50)),
            'is_confirmed'  => 0,
            'is_login'      => 0,
            'is_active'     => 0,
            'date'          => time(),
            'role_id'       => $roleId
        ];

        $created = $this->repo->createUser($user);
        return $created ? ['success' => true] : ['error' => 'Failed to register user'];
    }

    public function login(string $email, string $password)
    {
        $user = $this->repo->loginCheck($email, base64_encode($password));
        if ($user) {
            $this->repo->setLogin($user['id']);
        }
        return $user;
    }

    public function sendOTP(string $email)
    {
        $user = $this->repo->findByEmail($email);
        if (!$user) return false;

        $otp = str_pad(rand(0,999999), 6, '0', STR_PAD_LEFT);
        $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        $this->repo->updateOTP($user['id'], $otp, $expiry);

        $mail = new Mail();
        $mail->sendOTP($email, $otp);
        return true;
    }

    public function verifyOTP(string $email, string $otp)
    {
        return $this->repo->verifyOTP($email, $otp);
    }

    public function changePassword(string $email, string $newPassword)
    {
        $user = $this->repo->findByEmail($email);
        if (!$user) return false;

        return $this->repo->updatePassword($user['id'], base64_encode($newPassword));
    }

    public function changePasswordById(int $userId, string $password)
    {
        return $this->repo->updatePassword($userId, $password);
    }
}
