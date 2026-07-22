<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Controller\العملاءController;
use App\Repository\العملاءRepository;
use App\Entity\العملاء;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testالعملاء extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $entityManager;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(العملاءRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->entityManager->method('persist')->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);

        $this->controller = new العملاءController($this->entityManager, $this->router, $this->tokenStorage);
    }

    public function testGetAll()
    {
        $this->repository->method('findAll')->willReturn([$this->createMock(العملاء::class)]);
        $response = $this->controller->getAll();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn($this->createMock(العملاء::class));
        $response = $this->controller->getById($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetByIdNotFound()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(null);
        $response = $this->controller->getById($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testPost()
    {
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->repository->method('save')->with($this->createMock(العملاء::class))->willReturn($this->createMock(العملاء::class));
        $response = $this->controller->post($data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPostValidationFailed()
    {
        $data = ['name' => 'test'];
        $this->expectException(QueryException::class);
        $this->controller->post($data);
    }

    public function testPut()
    {
        $id = 1;
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->repository->method('find')->with($id)->willReturn($this->createMock(العملاء::class));
        $this->repository->method('save')->with($this->createMock(العملاء::class))->willReturn($this->createMock(العملاء::class));
        $response = $this->controller->put($id, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPutNotFound()
    {
        $id = 1;
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->repository->method('find')->with($id)->willReturn(null);
        $response = $this->controller->put($id, $data);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn($this->createMock(العملاء::class));
        $this->entityManager->method('remove')->with($this->createMock(العملاء::class))->willReturn(null);
        $this->entityManager->method('flush')->willReturn(null);
        $response = $this->controller->delete($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $id = 1;
        $this->repository->method('find')->with($id)->willReturn(null);
        $response = $this->controller->delete($id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}