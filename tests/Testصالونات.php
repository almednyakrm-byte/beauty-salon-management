<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\SalonController;
use App\Repository\SalonRepository;
use App\Entity\Salon;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\DBAL\Driver\PDOStatement;

class Testصالونات extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->controller = new SalonController($this->repository = $this->createMock(SalonRepository::class));
        $this->pdo = $this->createMock(PDOStatement::class);
    }

    public function testGetSalons()
    {
        $salons = [$this->createMock(Salon::class), $this->createMock(Salon::class)];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($salons);

        $response = $this->controller->getSalons();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($salons), $response->getContent());
    }

    public function testGetSalonById()
    {
        $salon = $this->createMock(Salon::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($salon);

        $response = $this->controller->getSalon(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($salon), $response->getContent());
    }

    public function testGetSalonByIdNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getSalon(1);
    }

    public function testCreateSalon()
    {
        $salon = $this->createMock(Salon::class);
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($this->anything());

        $request = new Request();
        $request->request->set('name', 'Salon Name');
        $request->request->set('address', 'Salon Address');

        $response = $this->controller->createSalon($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateSalon()
    {
        $salon = $this->createMock(Salon::class);
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($this->anything());

        $request = new Request();
        $request->request->set('name', 'Salon Name');
        $request->request->set('address', 'Salon Address');

        $response = $this->controller->updateSalon(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateSalonNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($this->anything());

        $request = new Request();
        $request->request->set('name', 'Salon Name');
        $request->request->set('address', 'Salon Address');

        $this->controller->updateSalon(1, $request);
    }

    public function testDeleteSalon()
    {
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($this->anything());

        $response = $this->controller->deleteSalon(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteSalonNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->pdo->expects($this->once())
            ->method('execute')
            ->with($this->anything());

        $this->controller->deleteSalon(1);
    }
}


This test file covers the following scenarios:

- `testGetSalons`: Tests the GET request for retrieving all salons.
- `testGetSalonById`: Tests the GET request for retrieving a salon by ID.
- `testGetSalonByIdNotFound`: Tests the GET request for retrieving a salon by ID when the salon does not exist.
- `testCreateSalon`: Tests the POST request for creating a new salon.
- `testUpdateSalon`: Tests the PUT request for updating an existing salon.
- `testUpdateSalonNotFound`: Tests the PUT request for updating a non-existent salon.
- `testDeleteSalon`: Tests the DELETE request for deleting a salon.
- `testDeleteSalonNotFound`: Tests the DELETE request for deleting a non-existent salon.

Note that this test file assumes that the `SalonController` class has methods for handling the above scenarios, and that the `SalonRepository` class has methods for interacting with the database.