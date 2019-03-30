<?php
namespace App\ExceptionListener;

use App\Exception\CommandNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ExceptionListener implements EventSubscriberInterface
{

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['commandNotFound', 10],
            ]
        ];
    }

    public function commandNotFound(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof CommandNotFoundException) {
            $response = new RedirectResponse($this->router->generate('home'));
            $event->setResponse($response);
        }


    }

}