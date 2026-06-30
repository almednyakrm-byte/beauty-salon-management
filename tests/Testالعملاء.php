<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\العملاءController;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testالعملاء extends TestCase
{
    private $controller;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->controller = new العملاءController($this->pdo);
    }

    public function testGetAll()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM العملاء')
            ->willReturn($this->createMock(\PDOStatement::class));

        $response = $this->controller->getAll();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM العملاء WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->getById($id);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'John Doe', 'email' => 'john@example.com'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO العملاء (name, email) VALUES (:name, :email)')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->exactly(2))
            ->method('bindParam')
            ->withConsecutive([':name', $data['name']], [':email', $data['email']]);

        $response = $this->controller->create($data);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Jane Doe', 'email' => 'jane@example.com'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE العملاء SET name = :name, email = :email WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->exactly(3))
            ->method('bindParam')
            ->withConsecutive([':name', $data['name']], [':email', $data['email']], [':id', $id]);

        $response = $this->controller->update($id, $data);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM العملاء WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));
        $this->pdo->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $response = $this->controller->delete($id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}