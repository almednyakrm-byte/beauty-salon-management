<?php

namespace App\Tests\Controller;

use App\Controller\ServicesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testخدمات extends TestCase
{
    private $pdoMock;
    private $servicesController;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->servicesController = new ServicesController($this->pdoMock);
    }

    public function testGetServices()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM services')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->servicesController->getServices($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostService()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO services (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['name' => 'Service 1', 'description' => 'This is a service']);
        $response = $this->servicesController->postService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutService()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE services SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => 1, 'name' => 'Service 1', 'description' => 'This is a service']);
        $response = $this->servicesController->putService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteService()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM services WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => 1]);
        $response = $this->servicesController->deleteService($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'خدمات' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the correct HTTP responses are returned for each operation.