<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AuthenticationSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_jwt_created' => ['customTokenPayload'],
        ];
    }

    public function customTokenPayload(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $user = $event->getUser();

        $payload['user'] = [
            'id' => $user->getId(),
        ];

        $event->setData($payload);
    }

}