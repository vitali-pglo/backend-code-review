<?php
declare(strict_types=1);

namespace App\Controller;

use App\Common\Response\InvalidRequest;
use App\Message\MessageStatus;
use App\Services\MessageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @see MessageControllerTest
 * TODO: review both methods and also the `openapi.yaml` specification
 *       Add Comments for your Code-Review, so that the developer can understand why changes are needed.
 */
class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageService      $messageService,
        private readonly SerializerInterface $serializer,
        private readonly InvalidRequest      $invalidRequest
    )
    {
    }

    /**
     * TODO: cover this method with tests, and refactor the code (including other files that need to be refactored)
     */
    #[Route('/messages', methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $status = $request->query->getInt('status') ?: null;

        if ($status) {
            try {
                $status = MessageStatus::from($status);
            } catch (\ValueError $exception) {
                $this->invalidRequest->setErrors(["Invalid status"]);
                return new Response($this->serializer->serialize($this->invalidRequest, 'json'), 400, headers: ['Content-Type' => 'application/json']);
            }
        }

        $messages = $this->messageService->getPaginated($status, $page);

        return new Response($this->serializer->serialize($messages, 'json'), headers: ['Content-Type' => 'application/json']);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/messages', methods: ['POST'])]
    public function send(Request $request): Response
    {
        $text = $request->request->get('text');

        if (!$text) {
            $this->invalidRequest->setErrors(["Text is required"]);
            return new Response($this->serializer->serialize($this->invalidRequest, 'json'), 400, headers: ['Content-Type' => 'application/json']);
        }

        $this->messageService->send((string)$text);

        return new Response(json_encode([
            'message' => 'Successfully sent'
        ]) ?: null, 200);
    }
}