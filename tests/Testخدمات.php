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
    private $servicesController;
    private $pdoMock;

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

        $response = $this->servicesController->getServices();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostService()
    {
        $request = new Request([], [], ['service' => 'test service']);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO services (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'test service']);

        $response = $this->servicesController->postService($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutService()
    {
        $request = new Request([], [], ['id' => 1, 'service' => 'updated service']);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE services SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => 'updated service', 'id' => 1]);

        $response = $this->servicesController->putService($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteService()
    {
        $request = new Request([], [], ['id' => 1]);
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM services WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $response = $this->servicesController->deleteService($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}