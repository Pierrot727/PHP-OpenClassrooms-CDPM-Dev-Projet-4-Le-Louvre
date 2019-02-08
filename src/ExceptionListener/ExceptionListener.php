<?php
namespace App\ExceptionListener;

use App\Exception\CommandNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['logException', 0],
                ['notifyException', -10],
            ]
        ];
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if(!$exception instanceof CommandNotFoundException){
            return ;
        }


    }

    public function logException(GetResponseForExceptionEvent $event)
    {
      /*  $exception = $event->getException();

        if(!$exception instanceof CommandNotFoundException){
            return ;
        }
        $logger->warning('No command was found');

        /* info code
    debug('normal');
    info('normal');
    notice('normal');
    warning('yellow');
    critical('red');
        */
    }

    public function notifyException(GetResponseForExceptionEvent $event)
    {
        // ...
    }
}