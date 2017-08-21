<?php

namespace PhpSolution\ApiUserBundle\EventSubscriber;

use PhpSolution\ApiUserBundle\Event\UserEvent;
use PhpSolution\ApiUserBundle\UserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ForgotPasswordSubscriber
 */
class ForgotPasswordSubscriber implements EventSubscriberInterface
{
    const SUBJECT = 'Forgot password';
    const BODY = 'To change password please use this code';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var string
     */
    private $sender;

    /**
     * @param \Swift_Mailer $mailer
     * @param string        $sender
     */
    public function __construct(\Swift_Mailer $mailer, string $sender)
    {
        $this->mailer = $mailer;
        $this->sender = $sender;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UserEvents::FORGOT_PASSWORD_TOKEN_CREATED => 'onForgotPassword',
        ];
    }

    /**
     * @param UserEvent $event
     */
    public function onForgotPassword(UserEvent $event): void
    {
        $user = $event->getUser();
        $body = sprintf(self::BODY . ' "%s"', $user->getConfirmationToken());

        $message = (new \Swift_Message(self::SUBJECT))
            ->setFrom($this->sender)
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}