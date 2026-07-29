<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ServicesController;
use App\Repository\ServicesRepository;
use App\Entity\Services;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testخدمات extends TestCase
{
    private $controller;
    private $repository;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ServicesRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new ServicesController($this->repository, $this->router);
    }

    public function testGetServices()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Services('Service 1'),
                new Services('Service 2'),
            ]);

        $response = $this->controller->getServices($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostService()
    {
        $service = new Services('New Service');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($service));

        $this->request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'New Service']);

        $response = $this->controller->postService($this->request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutService()
    {
        $service = new Services('Updated Service');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($service);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($service));

        $this->request->expects($this->once())
            ->method('request')
            ->with('json')
            ->willReturn(['name' => 'Updated Service']);

        $response = $this->controller->putService(1, $this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteService()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Services('Service 1'));

        $response = $this->controller->deleteService(1, $this->request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetServices`: Tests the `getServices` method by mocking the `findAll` method of the `ServicesRepository` to return a list of services. It then checks if the response status code is 200 (OK) and the content type is JSON.
- `testPostService`: Tests the `postService` method by mocking the `save` method of the `ServicesRepository` to save a new service. It then checks if the response status code is 201 (Created) and the content type is JSON.
- `testPutService`: Tests the `putService` method by mocking the `find` method of the `ServicesRepository` to return a service, and then the `save` method to save the updated service. It then checks if the response status code is 200 (OK) and the content type is JSON.
- `testDeleteService`: Tests the `deleteService` method by mocking the `find` method of the `ServicesRepository` to return a service. It then checks if the response status code is 204 (No Content).