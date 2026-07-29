<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\Call;
use PHPUnit\Framework\MockObject\InvokedArgs;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);
    }

    public function testLoginSuccess(): void
    {
        $username = 'testuser';
        $password = 'testpassword';
        $expectedUser = new User($username, $password);

        $this->authRepository
            ->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn($expectedUser);

        $actualUser = $this->authService->login($username, $password);
        $this->assertEquals($expectedUser, $actualUser);
    }

    public function testLoginFailure(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository
            ->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn(null);

        $actualUser = $this->authService->login($username, $password);
        $this->assertNull($actualUser);
    }

    public function testRegisterSuccess(): void
    {
        $username = 'testuser';
        $password = 'testpassword';
        $expectedUser = new User($username, $password);

        $this->authRepository
            ->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn($expectedUser);

        $actualUser = $this->authService->register($username, $password);
        $this->assertEquals($expectedUser, $actualUser);
    }

    public function testRegisterFailure(): void
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository
            ->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn(null);

        $actualUser = $this->authService->register($username, $password);
        $this->assertNull($actualUser);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method of `AuthService` returns the expected user object when the login credentials are correct.
- `testLoginFailure`: Tests that the `login` method of `AuthService` returns `null` when the login credentials are incorrect.
- `testRegisterSuccess`: Tests that the `register` method of `AuthService` returns the expected user object when the registration credentials are correct.
- `testRegisterFailure`: Tests that the `register` method of `AuthService` returns `null` when the registration credentials are incorrect.

Each test method uses the `createMock` method to create a mock object for the `AuthRepository` class, and then uses the `expects` method to specify the expected behavior of the mock object. The `willReturn` method is used to specify the return value of the mock object.