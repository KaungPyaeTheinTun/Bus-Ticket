<?php

interface AuthRepositoryInterface
{
    public function findByEmail(string $email);
    public function getById(string $userId);
    public function createUser(array $data);
    public function verifyToken(string $token);
    public function loginCheck(string $email, string $password);
    public function setLogin(int $id);
    public function unsetLogin(int $id);
    public function updateOTP(int $userId, string $otp, string $expiry);
    public function verifyOTP(string $email, string $otp);
    public function updatePassword(int $userId, string $password);
}
