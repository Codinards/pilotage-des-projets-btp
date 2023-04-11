<?php

namespace App\EventSubscriber;

use App\Exception\ThrowableException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof AccessDeniedHttpException) {
            $event->setResponse(
                new RedirectResponse(
                    $this->router->generate('app_login', ['_locale' => $event->getRequest()->getLocale()])
                )
            );
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
