<?php
// src/EventSubscriber/MailLoggerSubscriber.php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mailer\MailerEvents;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class MailLoggerSubscriber implements EventSubscriberInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            MailerEvents::MESSAGE_SENT => 'onMessageSent',
        ];
    }

    public function onMessageSent(MessageEvent $event)
    {
        $message = $event->getMessage();

        if ($message instanceof Email) {
            $toAddresses = array_map(fn($address) => $address->getAddress(), $message->getTo());
            $logEntry = sprintf(
                "[%s] Email sent to: %s\n",
                date('Y-m-d H:i:s'),
                implode(', ', $toAddresses)
            );

            // Chemin vers le fichier de log
            $logFilePath = __DIR__ . '/../../var/logs/mail.log';

            // Ecrire dans le fichier de log
            file_put_contents($logFilePath, $logEntry, FILE_APPEND);
        }
    }
}
