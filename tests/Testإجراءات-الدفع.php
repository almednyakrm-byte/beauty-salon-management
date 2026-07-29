<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProceduresPaymentController;
use App\Repository\ProceduresPaymentRepository;
use App\Entity\ProceduresPayment;
use App\Service\ProceduresPaymentService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testإجراءات-الدفع extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ProceduresPaymentRepository::class);
        $this->service = $this->createMock(ProceduresPaymentService::class);
        $this->controller = new ProceduresPaymentController($this->repository, $this->service);
    }

    public function testGetAll(): void
    {
        $procedurePayments = [
            new ProceduresPayment(),
            new ProceduresPayment(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($procedurePayments);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($procedurePayments), $response->getContent());
    }

    public function testGetById(): void
    {
        $procedurePayment = new ProceduresPayment();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($procedurePayment);

        $response = $this->controller->getById(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($procedurePayment), $response->getContent());
    }

    public function testGetByIdNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getById(1);
    }

    public function testCreate(): void
    {
        $procedurePayment = new ProceduresPayment();
        $procedurePayment->setId(1);

        $this->service->expects($this->once())
            ->method('create')
            ->with($procedurePayment)
            ->willReturn($procedurePayment);

        $request = new Request();
        $request->request->set('name', 'Test Procedure Payment');
        $request->request->set('description', 'Test Description');

        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($procedurePayment), $response->getContent());
    }

    public function testUpdate(): void
    {
        $procedurePayment = new ProceduresPayment();
        $procedurePayment->setId(1);

        $this->service->expects($this->once())
            ->method('update')
            ->with($procedurePayment)
            ->willReturn($procedurePayment);

        $request = new Request();
        $request->request->set('name', 'Test Procedure Payment Updated');
        $request->request->set('description', 'Test Description Updated');

        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($procedurePayment), $response->getContent());
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->service->expects($this->once())
            ->method('update')
            ->with(new ProceduresPayment())
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Test Procedure Payment Updated');
        $request->request->set('description', 'Test Description Updated');

        $this->controller->update(1, $request);
    }

    public function testDelete(): void
    {
        $procedurePayment = new ProceduresPayment();
        $procedurePayment->setId(1);

        $this->service->expects($this->once())
            ->method('delete')
            ->with($procedurePayment)
            ->willReturn(true);

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->service->expects($this->once())
            ->method('delete')
            ->with(new ProceduresPayment())
            ->willReturn(false);

        $this->controller->delete(1);
    }
}