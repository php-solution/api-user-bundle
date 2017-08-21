<?php

namespace PhpSolution\ApiUserBundle\EventSubscriber;

use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * RegistrationConfirmationSubscriber
 */
class RegistrationConfirmationSubscriber implements EventSubscriberInterface
{
    const SUBJECT = 'Please, confirm your account';
    const BODY = 'To complete the registration, please follow the link';

    /**
     * @var UrlGeneratorInterface
     */
    private $router;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $sender;

    /**
     * @param UrlGeneratorInterface $router
     * @param \Swift_Mailer         $mailer
     * @param string                $sender
     */
    public function __construct(UrlGeneratorInterface $router, \Swift_Mailer $mailer, string $sender)
    {
        $this->router = $router;
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function onRegistrationCompleted(UserEvent $event): void
    {
        $user = $event->getUser();
        $link = $this->router->generate('/user/confirm', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $body = sprintf(self::BODY . ' %s', $link);

        $message = (new \Swift_Message(self::SUBJECT))
            ->setFrom($this->sender)
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}