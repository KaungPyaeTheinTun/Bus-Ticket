<?php

use PHPUnit\Framework\TestCase;

require_once APPROOT . '/services/AuthService.php';
require_once APPROOT . '/helpers/UserValidator.php';


class AuthServiceTest extends TestCase
{
    private $authService;
    private $mockRepo;

    protected function setUp(): void
    {
        // Mock AuthRepository to avoid real DB calls
        $this->mockRepo = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->mockRepo);
    }

    // -------- REGISTER TESTS --------
    public function testRegisterUserEmailAlreadyExists()
    {
        $this->mockRepo
             ->method('findByEmail')
             ->willReturn(['id' => 1]);

        $result = $this->authService->registerUser([
            'email' => 'existing@example.com',
            'name' => 'Test',
            'phone' => '123456',
            'password' => 'password123'
        ]);

        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('This email is already registered!', $result['error']);
    }

    public function testRegisterUserSuccess()
    {
        $this->mockRepo
             ->method('findByEmail')
             ->willReturn(false);

        $this->mockRepo
             ->method('createUser')
             ->willReturn(true);

        $data = [
            'email' => 'newuser@example.com',
            'name' => 'New User',
            'phone' => '987654321',
            'password' => 'securepassword'
        ];

        $result = $this->authService->registerUser($data, 2, true);

        $this->assertArrayHasKey('success', $result);
        $this->assertTrue($result['success']);
    }

    // -------- LOGIN TESTS --------
    public function testLoginSuccess()
    {
        $email = 'user@example.com';
        $password = 'password123';
        $encodedPassword = base64_encode($password);

        $userData = ['id' => 5, 'email' => $email, 'role_id' => 1];

        $this->mockRepo
             ->method('loginCheck')
             ->with($email, $encodedPassword)
             ->willReturn($userData);

        $this->mockRepo
             ->expects($this->once())
             ->method('setLogin')
             ->with($userData['id']);

        $user = $this->authService->login($email, $password);

        $this->assertEquals($userData, $user);
    }

    public function testLoginFail()
    {
        $email = 'user@example.com';
        $password = 'wrongpass';

        $this->mockRepo->method('loginCheck')->willReturn(false);

        $user = $this->authService->login($email, $password);

        $this->assertFalse($user);
    }

    // -------- CHANGE PASSWORD TESTS --------
    public function testChangePassjwordSuccess()
    {
        $email = 'user@example.com';
        $this->mockRepo->method('findByEmail')->willReturn(['id' => 10]);
        $this->mockRepo->method('updatePassword')->willReturn(true);

        $result = $this->authService->changePassword($email, 'newpass');

        $this->assertTrue($result);
    }

    public function testChangePasswordFailNoUser()
    {
        $this->mockRepo->method('findByEmail')->willReturn(false);

        $result = $this->authService->changePassword('nouser@example.com', 'pass');
        $this->assertFalse($result);
    }

    public function testChangePasswordById()
    {
        $this->mockRepo->method('updatePassword')->willReturn(true);

        $result = $this->authService->changePasswordById(1, 'encodedpass');
        $this->assertTrue($result);
    }
}
