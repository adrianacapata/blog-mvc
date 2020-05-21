<?php

namespace Blog\Validator;

use Blog\Model\Entity\NewsletterEntity;
use Blog\Router\Request;

class UnsubscribeValidator
{
    /** @var Request */
    private $request;
    /** @var array */
    private $errors = [];
    /** @var bool  */
    private $isValid = true;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return NewsletterEntity|null
     */
    public function validate(): ?NewsletterEntity
    {
        $queryParameters = $this->request->getQueryParameters();
        $email = $queryParameters['email'] ?? null;

        $this->validateEmail($email);

        if ($this->isValid) {
            $newsletterEntity = new NewsletterEntity();
            $newsletterEntity->setEmail($email);

            return $newsletterEntity;
        }

        return null;
    }


    /**
     * @return array of input fields error messages
     */
    public function getValidationErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string|null $email
     */
    private function validateEmail(?string $email): void
    {
        if (!$email) {
            $this->errors['email'] = 'Email field is required';
            $this->isValid = false;
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email is not a valid address';
            $this->isValid = false;
        }
    }
}