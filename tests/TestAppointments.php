<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\AppointmentsController;
use App\Repository\AppointmentsRepository;
use App\Entity\Appointments;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;

class TestAppointments extends TestCase
{
    private $appointmentsController;
    private $appointmentsRepository;
    private $entityManager;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->appointmentsRepository = $this->createMock(AppointmentsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->appointmentsController = new AppointmentsController(
            $this->appointmentsRepository,
            $this->entityManager,
            $this->router
        );
    }

    public function testGetAppointments(): void
    {
        $appointments = [
            new Appointments(),
            new Appointments(),
        ];

        $this->appointmentsRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn($appointments);

        $response = $this->appointmentsController->getAppointments($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateAppointment(): void
    {
        $appointment = new Appointments();
        $appointment->setName('Test Appointment');
        $appointment->setDate('2024-01-01');

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($appointment);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->request
            ->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Test Appointment', 'date' => '2024-01-01']);

        $response = $this->appointmentsController->createAppointment($this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateAppointment(): void
    {
        $appointment = new Appointments();
        $appointment->setId(1);
        $appointment->setName('Test Appointment');
        $appointment->setDate('2024-01-01');

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Appointments::class, 1)
            ->willReturn($appointment);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $this->request
            ->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Updated Test Appointment', 'date' => '2024-01-01']);

        $response = $this->appointmentsController->updateAppointment(1, $this->request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteAppointment(): void
    {
        $appointment = new Appointments();
        $appointment->setId(1);

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(Appointments::class, 1)
            ->willReturn($appointment);

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($appointment);

        $this->entityManager
            ->expects($this->once())
            ->method('flush')
            ->with();

        $response = $this->appointmentsController->deleteAppointment(1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}