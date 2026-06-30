<?php

namespace App\Tests\Controller;

use App\Controller\دفتر المبيعاتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class Testدفتر-المبيعات extends TestCase
{
    private $controller;
    private $tokenStorage;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->pdoMock = $this->createMock(\PDO::class);
        $this->controller = new دفتر المبيعاتController($this->pdoMock, $this->tokenStorage);
    }

    public function testGetAll()
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM دفتر_المبيعات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('GET');
        $response = $this->controller->getAll($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO دفتر_المبيعات (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'Test Name');
        $request->request->set('description', 'Test Description');
        $response = $this->controller->create($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE دفتر_المبيعات SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('id', 1);
        $request->request->set('name', 'Test Name');
        $request->request->set('description', 'Test Description');
        $response = $this->controller->update($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM دفتر_المبيعات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $request->setMethod('DELETE');
        $request->request->set('id', 1);
        $response = $this->controller->delete($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


Note: This code assumes that the `دفتر المبيعاتController` class is located in the `App\Controller` namespace and that it has methods for CRUD operations. The `TokenStorageInterface` and `TokenInterface` classes are also assumed to be part of the Symfony security component.