<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TimetableController;
use App\Repository\TimetableRepository;
use App\Entity\Timetable;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestTimetable extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TimetableRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new TimetableController($this->repository, $this->router);
    }

    public function testGetTimetables()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Timetable(),
                new Timetable(),
            ]);

        $request = new Request();
        $response = $this->controller->getTimetables($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateTimetable()
    {
        $timetable = new Timetable();
        $timetable->setName('Test Timetable');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($timetable));

        $request = new Request([], [], ['timetable' => $timetable]);
        $response = $this->controller->createTimetable($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateTimetable()
    {
        $timetable = new Timetable();
        $timetable->setId(1);
        $timetable->setName('Test Timetable');

        $this->repository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn($timetable);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($timetable));

        $request = new Request([], [], ['timetable' => $timetable]);
        $response = $this->controller->updateTimetable($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteTimetable()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn(new Timetable());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($this->equalTo(new Timetable()));

        $request = new Request();
        $response = $this->controller->deleteTimetable($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}