<?php

namespace Blog\Validator;

use Blog\Router\Request;

class SearchValidator
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

    public function validate(): ?string
    {
        $getParameters = $this->request->getQueryParameters();
        $word = $getParameters['q'] ?? null;
        $this->validateWord($word);

        if ($this->isValid) {
            return $word;
        }

        return null;
    }

    /**
     * Returns an array with error messages if word is not valid
     */
    public function getValidationErrors(): array
    {
        return $this->errors;
    }

    private function validateWord(?string $word): void
    {
        if (preg_match('/[^a-zA-Z0-9_\-\s]+/', $word)) {
            $this->errors['searched_word'] = 'this is not a valid word';
            $this->isValid = false;
        }
    }
}