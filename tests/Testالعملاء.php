<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\العملاءController;
use App\Repository\العملاءRepository;
use App\Entity\العملاء;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالعملاء extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(العملاءRepository::class);
        $this->controller = new العملاءController($this->repository);

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('exec')->willReturn(1);
        $this->pdo->method('commit')->willReturn(1);
        $this->pdo->method('rollBack')->willReturn(1);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([
            new العملاء(),
            new العملاء(),
        ]);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $response->getContent());
    }

    public function testGetById()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(new العملاء());

        $response = $this->controller->getById($id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($id, $response->getContent()->getId());
    }

    public function testGetByIdNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->getById($id);
    }

    public function testCreate()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO العملاء (name, email) VALUES (:name, :email)');

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with(':name', ':email');

        $response = $this->controller->create($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->repository->method('find')->with($id)->willReturn(new العملاء());

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE العملاء SET name = :name, email = :email WHERE id = :id');

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with(':name', ':email', ':id');

        $response = $this->controller->update($id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ];

        $this->expectException(NotFoundHttpException::class);
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->update($id, $data);
    }

    public function testDelete()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM العملاء WHERE id = :id');

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with(':id');

        $response = $this->controller->delete($id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;

        $this->expectException(NotFoundHttpException::class);
        $this->repository->method('find')->with($id)->willReturn(null);
        $this->controller->delete($id);
    }
}


This test file covers the following scenarios:

*   `testGetAll`: Verifies that the `getAll` method returns a list of all customers.
*   `testGetById`: Verifies that the `getById` method returns a customer by ID.
*   `testGetByIdNotFound`: Verifies that the `getById` method throws a `NotFoundHttpException` when the customer is not found.
*   `testCreate`: Verifies that the `create` method creates a new customer.
*   `testUpdate`: Verifies that the `update` method updates an existing customer.
*   `testUpdateNotFound`: Verifies that the `update` method throws a `NotFoundHttpException` when the customer is not found.
*   `testDelete`: Verifies that the `delete` method deletes a customer.
*   `testDeleteNotFound`: Verifies that the `delete` method throws a `NotFoundHttpException` when the customer is not found.

Note that this test file uses a mocked PDO object to simulate database interactions. The `createMock` method is used to create mock objects for the `PDO` and `العملاءRepository` classes. The `expects` method is used to specify the expected behavior of the mock objects.