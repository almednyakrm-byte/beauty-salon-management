<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\RecommendationsRepository;
use App\Service\RecommendationsService;
use PHPUnit\Framework\MockObject\MockObject;

class Testتوصيات-الأزياء extends TestCase
{
    private $client;
    private $router;
    private $recommendationsRepository;
    private $recommendationsService;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->router = $this->createMock(RouterInterface::class);
        $this->recommendationsRepository = $this->createMock(RecommendationsRepository::class);
        $this->recommendationsService = $this->createMock(RecommendationsService::class);

        $this->client->setRouter($this->router);
        $this->client->setRequest(new Request());
    }

    public function testGetRecommendations()
    {
        $this->recommendationsRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Recommendation 1'],
                ['id' => 2, 'name' => 'Recommendation 2'],
            ]);

        $this->recommendationsService->expects($this->once())
            ->method('getRecommendations')
            ->willReturn($this->recommendationsRepository->findAll());

        $this->router->expects($this->once())
            ->method('generate')
            ->with('recommendations_index')
            ->willReturn('/recommendations');

        $this->client->request('GET', '/recommendations');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testCreateRecommendation()
    {
        $this->recommendationsRepository->expects($this->once())
            ->method('create')
            ->with(['name' => 'New Recommendation'])
            ->willReturn(['id' => 3, 'name' => 'New Recommendation']);

        $this->recommendationsService->expects($this->once())
            ->method('createRecommendation')
            ->willReturn($this->recommendationsRepository->create(['name' => 'New Recommendation']));

        $this->router->expects($this->once())
            ->method('generate')
            ->with('recommendations_create')
            ->willReturn('/recommendations/create');

        $this->client->request('POST', '/recommendations', ['name' => 'New Recommendation']);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
    }

    public function testUpdateRecommendation()
    {
        $this->recommendationsRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Recommendation 1']);

        $this->recommendationsService->expects($this->once())
            ->method('updateRecommendation')
            ->with(1, ['name' => 'Updated Recommendation'])
            ->willReturn(['id' => 1, 'name' => 'Updated Recommendation']);

        $this->router->expects($this->once())
            ->method('generate')
            ->with('recommendations_update', ['id' => 1])
            ->willReturn('/recommendations/1/update');

        $this->client->request('PUT', '/recommendations/1/update', ['name' => 'Updated Recommendation']);

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testDeleteRecommendation()
    {
        $this->recommendationsRepository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $this->recommendationsService->expects($this->once())
            ->method('deleteRecommendation')
            ->with(1)
            ->willReturn($this->recommendationsRepository->delete(1));

        $this->router->expects($this->once())
            ->method('generate')
            ->with('recommendations_delete', ['id' => 1])
            ->willReturn('/recommendations/1/delete');

        $this->client->request('DELETE', '/recommendations/1/delete');

        $this->assertEquals(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  `testGetRecommendations`: Verifies that the `GET /recommendations` endpoint returns a list of recommendations.
2.  `testCreateRecommendation`: Tests the creation of a new recommendation using the `POST /recommendations` endpoint.
3.  `testUpdateRecommendation`: Updates an existing recommendation using the `PUT /recommendations/{id}/update` endpoint.
4.  `testDeleteRecommendation`: Deletes a recommendation using the `DELETE /recommendations/{id}/delete` endpoint.

Each test method uses the `createMock` method to create mock objects for the `RecommendationsRepository` and `RecommendationsService` classes. These mock objects are used to simulate the behavior of the real classes during the test.

The `setUp` method is used to create a new client instance and set up the router and request objects.

The test methods use the `expects` method to specify the expected behavior of the mock objects. They also use the `willReturn` method to specify the return values for the mock objects.

Finally, the test methods use the `assertEquals` method to verify that the response status code matches the expected value.