<?php

namespace Blog\Command;

use Blog\DependencyInjection\Container;
use Blog\Model\Repository\BlogRepository;
use Blog\Router\Response\Response;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SendMailCommand implements CommandInterface
{
    public function execute()
    {
        $this->sendMail();
    }

    private function sendMail(): void
    {

        $swiftmailer = Container::getParameters('swiftmailer');

        $transport = (new Swift_SmtpTransport($swiftmailer['host'], $swiftmailer['port'], $swiftmailer['encryption']))
            ->setUsername($swiftmailer['sender_address'])
            ->setPassword($swiftmailer['password'])
        ;

        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message('Most popular posts'))
            ->setFrom([$swiftmailer['sender_address'] => 'Adriana Capata'])
            ->setTo($swiftmailer['delivery_address'])
            ->setBody($this->getHtmlBody(), 'text/html')
        ;

        $mailer->send($message);
    }

    private function getHtmlBody(): string
    {
        $popularPosts = BlogRepository::getPopularity();

        ob_start();
        $response = new Response('command\newsletter.php', [
          'posts' => $popularPosts,
        ]);
        $response->render();

        return ob_get_clean();
    }
}