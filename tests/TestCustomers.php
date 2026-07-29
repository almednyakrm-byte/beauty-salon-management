<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\CustomersController;
use App\Repository\CustomersRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestCustomers extends TestCase
{
    private $customersController;
    private $customersRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->customersRepository = $this->createMock(CustomersRepository::class);
        $this->pdo = $this->createMock(PDO::class);
        $this->customersController = new CustomersController($this->customersRepository, $this->pdo);
    }

    public function testGetCustomers()
    {
        $expectedCustomers = [
            ['id' => 1, 'name' => 'John Doe'],
            ['id' => 2, 'name' => 'Jane Doe'],
        ];

        $this->customersRepository->expects($this->once())
            ->method('getAllCustomers')
            ->willReturn($expectedCustomers);

        $response = $this->customersController->getCustomers();
        $this->assertEquals($expectedCustomers, $response);
    }

    public function testCreateCustomer()
    {
        $customerData = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $expectedCustomer = ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO customers (name, email) VALUES (:name, :email)', ['name' => $customerData['name'], 'email' => $customerData['email']]);

        $this->customersRepository->expects($this->once())
            ->method('getLastInsertedId')
            ->willReturn(1);

        $response = $this->customersController->createCustomer($customerData);
        $this->assertEquals($expectedCustomer, $response);
    }

    public function testUpdateCustomer()
    {
        $customerData = ['id' => 1, 'name' => 'John Doe Updated', 'email' => 'john@example.com'];
        $expectedCustomer = ['id' => 1, 'name' => 'John Doe Updated', 'email' => 'john@example.com'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE customers SET name = :name, email = :email WHERE id = :id', ['name' => $customerData['name'], 'email' => $customerData['email'], 'id' => $customerData['id']]);

        $this->customersRepository->expects($this->once())
            ->method('getCustomerById')
            ->with($customerData['id'])
            ->willReturn(['id' => $customerData['id'], 'name' => $customerData['name'], 'email' => $customerData['email']]);

        $response = $this->customersController->updateCustomer($customerData);
        $this->assertEquals($expectedCustomer, $response);
    }

    public function testDeleteCustomer()
    {
        $customerId = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock(PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM customers WHERE id = :id', ['id' => $customerId]);

        $response = $this->customersController->deleteCustomer($customerId);
        $this->assertTrue($response);
    }
}


This test file covers the following scenarios:

1. `testGetCustomers`: Verifies that the `getCustomers` method returns a list of customers.
2. `testCreateCustomer`: Verifies that the `createCustomer` method creates a new customer and returns the created customer data.
3. `testUpdateCustomer`: Verifies that the `updateCustomer` method updates an existing customer and returns the updated customer data.
4. `testDeleteCustomer`: Verifies that the `deleteCustomer` method deletes a customer and returns `true`.

Note that this test file uses mocking to isolate the dependencies of the `CustomersController` class, making it easier to test the class in isolation.