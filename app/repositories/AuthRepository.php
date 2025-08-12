<?php

require_once APPROOT . '/interfaces/AuthRepositoryInterface.php';

require_once APPROOT . '/config/DBconnection.php';

class AuthRepository extends DBconnection implements AuthRepositoryInterface
{    
    public function __construct()
    {
        parent::__construct();//Do the parent's version of this method.
    }
    
    public function findByEmail(string $email)
    {
        return $this->getDB()->columnFilter('users', 'email', $email);
    }

    public function createUser(array $data)
    {
        return $this->getDB()->create('users', $data);
    }

    public function verifyToken(string $token)
    {
        return $this->getDB()->columnFilter('users', 'token', $token);
    }

    public function loginCheck(string $email, string $password)
    {
        return $this->getDB()->loginCheck($email, $password);
    }

    public function setLogin(int $id)
    {
        return $this->getDB()->setLogin($id);
    }

    public function unsetLogin(int $id)
    {
        return $this->getDB()->unsetLogin($id);
    }

    public function updateOTP(int $userId, string $otp, string $expiry)
    {
        return $this->getDB()->update('users', $userId, ['otp' => $otp,'otp_expiry' => $expiry]);
    }

    public function verifyOTP(string $email, string $otp)
    {
        return $this->getDB()->otpcheck($email, $otp);
    }

    public function updatePassword(int $userId, string $password)
    {
        return $this->getDB()->update('users', $userId, ['password' => $password]);
    }
}
