<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MoawaedController;
use App\Repository\MoawaedRepository;
use App\Entity\Moawaed;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class TestMoawaed extends TestCase
{
    private $moawaedController;
    private $moawaedRepository;
    private $entityManager;

    public function setUp(): void
    {
        $this->moawaedRepository = $this->createMock(MoawaedRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->moawaedController = new MoawaedController($this->moawaedRepository, $this->entityManager);
    }

    public function testGetMoawaeds()
    {
        $moawaeds = [new Moawaed(), new Moawaed()];
        $this->moawaedRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($moawaeds);

        $response = $this->moawaedController->getMoawaeds();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($moawaeds, json_decode($response->getContent(), true));
    }

    public function testGetMoawaed()
    {
        $moawaed = new Moawaed();
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moawaed);

        $response = $this->moawaedController->getMoawaed(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($moawaed, json_decode($response->getContent(), true));
    }

    public function testGetMoawaedNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->moawaedController->getMoawaed(1);
    }

    public function testCreateMoawaed()
    {
        $moawaed = new Moawaed();
        $moawaed->setId(1);
        $this->moawaedRepository->expects($this->once())
            ->method('save')
            ->with($moawaed)
            ->willReturn($moawaed);

        $request = new Request([], [], ['moawaed' => ['name' => 'test']]);
        $response = $this->moawaedController->createMoawaed($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($moawaed, json_decode($response->getContent(), true));
    }

    public function testUpdateMoawaed()
    {
        $moawaed = new Moawaed();
        $moawaed->setId(1);
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moawaed);
        $this->moawaedRepository->expects($this->once())
            ->method('save')
            ->with($moawaed)
            ->willReturn($moawaed);

        $request = new Request([], [], ['moawaed' => ['name' => 'test']]);
        $response = $this->moawaedController->updateMoawaed(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($moawaed, json_decode($response->getContent(), true));
    }

    public function testUpdateMoawaedNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['moawaed' => ['name' => 'test']]);
        $this->moawaedController->updateMoawaed(1, $request);
    }

    public function testDeleteMoawaed()
    {
        $moawaed = new Moawaed();
        $moawaed->setId(1);
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($moawaed);
        $this->moawaedRepository->expects($this->once())
            ->method('remove')
            ->with($moawaed);

        $response = $this->moawaedController->deleteMoawaed(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteMoawaedNotFound()
    {
        $this->expectException(NotFoundHttpException::class);
        $this->moawaedRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->moawaedController->deleteMoawaed(1);
    }
}