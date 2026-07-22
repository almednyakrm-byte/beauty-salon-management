<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Service\ServicesService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;

class TestServices extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $request;
    private $route;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ServicesRepository::class);
        $this->service = $this->createMock(ServicesService::class);
        $this->controller = new ServicesController($this->repository, $this->service);
        $this->request = $this->createMock(Request::class);
        $this->route = new Route('/services');
    }

    public function testGetServices(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Service 1'],
                ['id' => 2, 'name' => 'Service 2'],
            ]);

        $response = $this->controller->getServices($this->request, $this->route);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostService(): void
    {
        $data = ['name' => 'New Service'];
        $this->request->expects($this->once())
            ->method('request')
            ->with('POST')
            ->willReturn($data);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($data);

        $response = $this->controller->postService($this->request, $this->route);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutService(): void
    {
        $data = ['id' => 1, 'name' => 'Updated Service'];
        $this->request->expects($this->once())
            ->method('request')
            ->with('PUT')
            ->willReturn($data);

        $this->repository->expects($this->once())
            ->method('update')
            ->with($data);

        $response = $this->controller->putService($this->request, $this->route);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteService(): void
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->deleteService($this->request, $this->route);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'Services' module. It creates a mock object for the ServicesRepository and ServicesService classes, and uses these mocks to test the ServicesController class. The tests cover the GET, POST, PUT, and DELETE requests.