<?php

namespace Blog\Command;

use ArrayObject;
use Blog\DependencyInjection\Container;
use Blog\DependencyInjection\Mailer;
use Blog\Model\Entity\BlogEntity;
use Blog\Model\Entity\NewsletterEntity;
use Blog\Model\Repository\BlogRepository;
use Blog\Model\Repository\NewsletterRepository;
use Blog\Router\Exception\InvalidTemplateException;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class NewsletterCommand implements CommandInterface
{
    /**
     * @throws InvalidTemplateException
     */
    public function execute(): void
    {
        $posts = BlogRepository::getPopularity();
        $emails = NewsletterRepository::getAllEmailAddresses();

        $this->sendMail($posts, $emails);
    }

    /**
     * @param ArrayObject|BlogEntity[] $posts
     * @param ArrayObject|NewsletterEntity[] $emails
     * @throws InvalidTemplateException
     */
    private function sendMail($posts, $emails): void
    {
        $mailer = Container::getMailer();

        foreach ($emails as $email) {
            $mailer->sendMessage(
                'Popular posts',
                Container::getParameters('swift_mailer')['sender_address'],
                $email->getEmail(),
                '\subscription\newsletter.php',
                [
                    'posts' => $posts,
                    'email' => $email,
                ]
            );
        }
    }
}