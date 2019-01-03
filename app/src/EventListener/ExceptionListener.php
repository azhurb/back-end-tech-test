<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handling Not Found exceptions.
 *
 * Class ExceptionListener
 * @package App\EventListener
 */
class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {
            $response = new Response();
            $response->setContent(json_encode(['message' => 'Not found']));
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace(['Content-Type' => 'application/json']);
        }

        if (isset($response)) {
            $event->setResponse($response);
        }
    }
}