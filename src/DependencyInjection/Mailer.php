<?php

namespace Blog\DependencyInjection;

use Blog\Router\Exception\InvalidTemplateException;
use Blog\Router\Response\Response;
use Swift_Mailer;
use Swift_Message;

class Mailer
{
    private $swiftMailer;

    public function __construct(Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
    }

    /**
     * @param string $template
     * @param array $variables
     * @return string
     * @throws InvalidTemplateException
     */
    private function setMessageBody(string $template, array $variables = []): string
    {
        ob_start();

        $response = new Response($template, $variables);
        $response->render();

        return ob_get_clean();
    }

    /**
     * @param string $subject
     * @param string $senderAddress
     * @param string $deliveryAddress
     * @param string $template
     * @param array $templateVariables
     * @throws InvalidTemplateException
     */
    public function sendMessage(string $subject, string $senderAddress, string $deliveryAddress, string $template, array $templateVariables = []): void
    {
        $message = (new Swift_Message())
            ->setSubject($subject)
            ->setFrom([$senderAddress])
            ->setTo([$deliveryAddress])
            ->setBody($this->setMessageBody($template, $templateVariables), 'text/html');

        if ($this->swiftMailer->send($message)) {
            echo 'Mail was sent to address: ' . $deliveryAddress;
        } else {
            echo 'Mail was not sent to address: ' . $deliveryAddress;
        }
    }

}