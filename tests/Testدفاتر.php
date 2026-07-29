<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\DefterController;
use App\Repository\DefterRepository;
use App\Entity\Defter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;
use Symfony\Component\Paginator\Paginator;

class TestDefter extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(DefterRepository::class);
        $this->controller = new DefterController($this->repository);

        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('query')->willReturn($this->createMock('PDOStatement'));
        $this->pdo->method('exec')->willReturn(1);
        $this->pdo->method('commit')->willReturn(1);
        $this->pdo->method('rollBack')->willReturn(1);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([
            new Defter(1, 'دفتر 1'),
            new Defter(2, 'دفتر 2'),
        ]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertCount(2, $response->getContent());
    }

    public function testGetOne()
    {
        $this->repository->method('findOne')->willReturn(new Defter(1, 'دفتر 1'));

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('{"id":1,"name":"\u062f\u0641\u062a\u0631 1"}', $response->getContent());
    }

    public function testGetOneNotFound()
    {
        $this->repository->method('findOne')->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne($request, 1);
    }

    public function testCreate()
    {
        $defter = new Defter(1, 'دفتر 1');
        $this->repository->method('create')->willReturn($defter);

        $request = new Request();
        $request->request->set('name', 'دفتر 1');
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('{"id":1,"name":"\u062f\u0641\u062a\u0631 1"}', $response->getContent());
    }

    public function testUpdate()
    {
        $defter = new Defter(1, 'دفتر 1');
        $this->repository->method('update')->willReturn($defter);

        $request = new Request();
        $request->request->set('name', 'دفتر 2');
        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertEquals('{"id":1,"name":"\u062f\u0641\u062a\u0631 2"}', $response->getContent());
    }

    public function testUpdateNotFound()
    {
        $this->repository->method('update')->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->update($request, 1);
    }

    public function testDelete()
    {
        $this->repository->method('delete')->willReturn(1);

        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $this->repository->method('delete')->willReturn(0);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->delete($request, 1);
    }
}