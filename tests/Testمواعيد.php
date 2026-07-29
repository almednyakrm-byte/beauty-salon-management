<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TimetableController;
use App\Repository\TimetableRepository;
use App\Service\TimetableService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestTimetable extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(TimetableRepository::class);
        $this->service = $this->createMock(TimetableService::class);
        $this->controller = new TimetableController($this->repository, $this->service);
    }

    public function testGetTimetables()
    {
        $timetables = [
            ['id' => 1, 'name' => 'Timetable 1'],
            ['id' => 2, 'name' => 'Timetable 2'],
        ];

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM timetables')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($timetables);

        $response = $this->controller->getTimetables();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($timetables), $response->getBody()->getContents());
    }

    public function testCreateTimetable()
    {
        $timetable = ['id' => 1, 'name' => 'Timetable 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO timetables (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $timetable['name']]);

        $response = $this->controller->createTimetable($timetable);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($timetable), $response->getBody()->getContents());
    }

    public function testUpdateTimetable()
    {
        $timetable = ['id' => 1, 'name' => 'Timetable 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE timetables SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => $timetable['name'], 'id' => $timetable['id']]);

        $response = $this->controller->updateTimetable($timetable);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($timetable), $response->getBody()->getContents());
    }

    public function testDeleteTimetable()
    {
        $timetable = ['id' => 1, 'name' => 'Timetable 1'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM timetables WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => $timetable['id']]);

        $response = $this->controller->deleteTimetable($timetable['id']);
        $this->assertEquals(204, $response->getStatusCode());
    }
}



// TimetableController.php

namespace App\Controller;

use App\Repository\TimetableRepository;
use App\Service\TimetableService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TimetableController
{
    private $repository;
    private $service;

    public function __construct(TimetableRepository $repository, TimetableService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getTimetables(Request $request)
    {
        $timetables = $this->repository->findAll();
        return new JsonResponse($timetables, 200);
    }

    public function createTimetable(Request $request)
    {
        $timetable = json_decode($request->getContent(), true);
        $this->service->createTimetable($timetable);
        return new JsonResponse($timetable, 201);
    }

    public function updateTimetable(Request $request)
    {
        $timetable = json_decode($request->getContent(), true);
        $this->service->updateTimetable($timetable);
        return new JsonResponse($timetable, 200);
    }

    public function deleteTimetable(Request $request)
    {
        $id = $request->get('id');
        $this->service->deleteTimetable($id);
        return new Response('', 204);
    }
}