<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ExceptionResponseSubscriber implements EventSubscriberInterface
{

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => ['__invoke']];
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        if ($throwable instanceof HttpException) {
            if ($throwable->getPrevious() instanceof ValidationFailedException) {
                $responseData = $this->extractErrorListFromValidationFailedException($throwable->getPrevious());
                $response = new JsonResponse(
                    [
                        'message' => 'Validation failed.',
                        'errors' => $responseData,
                    ],
                    422
                );
            }
        }
        // todo: добавить обработку ошибок аутентификации (401), недостаточно прав доступа (403), не найдена сущность (404)
        if(!isset($response)) {
            $response = new JsonResponse(
                [
                    'message' => 'Internal error.',
                ],
                500
            );
        }
        $event->setResponse($response);
    }

    private function extractErrorListFromValidationFailedException(ValidationFailedException $exception)
    {
        $responseData = [];
        foreach ($exception->getViolations() as $violation) {
            /** @var ConstraintViolation $violation */
            $responseData[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }
        return $responseData;
    }
}