<?php

require_once APPROOT . '/repositories/AuthRepository.php';

class AuthService
{
    private $repo;

    public function __construct(AuthRepository $repo)
    {
        $this->repo = $repo;
    }

    public function registerUser(array $formData, int $roleId = 2, bool $skipValidation = false)//ROLE_USER
    {
        if ($this->repo->findByEmail($formData['email'])) {
            return ['error' => 'This email is already registered!'];
        }

        if (!$skipValidation) {
            $validator = new UserValidator($formData);
            $errors = $validator->validateForm();
            if (!empty($errors)) {
                return ['errors' => $errors];
            }
        }

        // Hash password securely (Argon2id or fallback bcrypt)
        $passwordHash = password_hash(
            $formData['password'],
            defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT
        );

        $user = [
            'name'          => $formData['name'],
            'phone'         => $formData['phone'],
            'email'         => $formData['email'],
            'password'      => $passwordHash, 
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
        $user = $this->repo->findByEmail($email);
        if (!$user) {
            return false;
        }

        // Verify password against hash
        if (password_verify($password, $user['password'])) {
            // Optionally rehash if needed (cost updated in future)
            if (password_needs_rehash($user['password'], PASSWORD_ARGON2ID)) {
                $newHash = password_hash($password, PASSWORD_ARGON2ID);
                $this->repo->updatePassword($user['id'], $newHash);
            }

            $this->repo->setLogin($user['id']);
            return $user;
        }

        return false;
    }

    public function sendsOTP(string $email)
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

        $passwordHash = password_hash(
            $newPassword,
            defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT
        );

        return $this->repo->updatePassword($user['id'], $passwordHash);
    }

    public function changePasswordById(int $userId, string $newPassword)
    {
        $passwordHash = password_hash(
            $newPassword,
            defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT
        );

        return $this->repo->updatePassword($userId, $passwordHash);
    }

    public function getUserById(int $userId)
    {
        return $this->repo->getById($userId);
    }
}
