<?php

require_once APPROOT . '/interfaces/AuthRepositoryInterface.php';

class AuthRepository implements AuthRepositoryInterface
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database();
    }

    public function findByEmail(string $email)
    {
        return $this->db->columnFilter('users', 'email', $email);
    }

    public function createUser(array $data)
    {
        return $this->db->create('users', $data);
    }

    public function verifyToken(string $token)
    {
        return $this->db->columnFilter('users', 'token', $token);
    }

    public function loginCheck(string $email, string $password)
    {
        return $this->db->loginCheck($email, $password);
    }

    public function setLogin(int $id)
    {
        return $this->db->setLogin($id);
    }

    public function unsetLogin(int $id)
    {
        return $this->db->unsetLogin($id);
    }

    public function updateOTP(int $userId, string $otp, string $expiry)
    {
        return $this->db->update('users', $userId, [
            'otp' => $otp,
            'otp_expiry' => $expiry
        ]);
    }

    public function verifyOTP(string $email, string $otp)
    {
        return $this->db->otpcheck($email, $otp);
    }

    public function updatePassword(int $userId, string $password)
    {
        return $this->db->update('users', $userId, ['password' => $password]);
    }
}
