<?php

namespace App\EventSubscriber\Auth;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessSubscriber implements EventSubscriberInterface
{

    private $secure = false;

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        $data = $event->getData();
        $user = $event->getUser();


        if (!$user instanceof UserInterface) {
            return;
        }

        $data['data'] =[
            'id'        => $user->getId(),
            'username'  => $user->getUsername(),
            'lastname'  => $user->getLastname(),
            'firstname' => $user->getFirstname(),
        ];

        $response = $event->getResponse();
        $token = $data["token"];
        $response->headers->setCookie(
            new Cookie('BEARER',$token,
                (new \DateTime())
                    ->add(new \DateInterval('PT'.time().'S')
                ),'/', null, $this->secure
            )
        );

        $event->setData($data);
    }

    public static function getSubscribedEvents()
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccessResponse',
        ];
    }
}
