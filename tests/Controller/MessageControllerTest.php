<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use App\Common\Response\InvalidRequest;
use App\Common\Response\PaginatedResponse;
use App\Controller\MessageController;
use App\Message\MessageStatus;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

class MessageControllerTest extends WebTestCase
{
    use InteractsWithMessenger;

    private MessageService $messageService;
    private SerializerInterface $serializer;
    private InvalidRequest $invalidRequest;
    private AbstractController $controller;

    protected function setUp(): void
    {
        $this->messageService = $this->createMock(MessageService::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->invalidRequest = $this->createMock(InvalidRequest::class);
        $this->controller = new MessageController(
            $this->messageService,
            $this->serializer,
            $this->invalidRequest
        );
    }

    public function testListWithValidStatus(): void
    {
        $request = new Request(['status' => 1, 'page' => 1]);
        $paginatedResponse = $this->createMock(PaginatedResponse::class);

        $this->messageService->expects($this->once())
            ->method('getPaginated')
            ->with($this->isInstanceOf(MessageStatus::class), 1)
            ->willReturn($paginatedResponse);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->willReturn('[]');

        $response = $this->controller->list($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testSendMessageWithText(): void
    {
        $client = static::createClient();
        $client->request('POST', '/messages', ['text' => 'Hello, World!']);

        $this->assertResponseIsSuccessful();
        $this->assertJsonResponse($client->getResponse(), [
            'message' => 'Successfully sent'
        ]);
    }

    public function testSendMessageWithoutText(): void
    {
        $client = static::createClient();
        $client->request('POST', '/messages');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonResponse($client->getResponse(), [
            'errors' => ['Text is required']
        ]);
    }

    /**
     * @param Response $response
     * @param mixed[] $expectedData
     * @return void
     */
    private function assertJsonResponse(Response $response, array $expectedData): void
    {
        $data = json_decode($response->getContent(), true);
        foreach ($expectedData as $key => $value) {
            $this->assertArrayHasKey($key, $data);
            $this->assertEquals($value, $data[$key]);
        }
    }
}