<?php

namespace Blog\Validator;

use Blog\Model\Entity\CommentEntity;
use Blog\Router\Request;

class CommentValidator
{

    /** @var Request */
    private $request;

    private $errors = [];

    private $isValid = true;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns a validated Comment Entity or null
     *
     * @param string $author
     * @param string $comment
     * @return CommentEntity|null
     */
    public function validate()
    {
        $blogId = (int) $this->request->getPostParameters()['blog_id'];
        $author = $this->request->getPostParameters()['author_name'];
        $comment = $this->request->getPostParameters()['comment'];

        $commentEntity = null;

        $this->validateBlogId($blogId);
        $this->validateAuthor($author);
        $this->validateComment($comment);

        if ($this->isValid) {
            $commentEntity = new CommentEntity();
            $commentEntity->setBlogId($blogId);
            $commentEntity->setAuthorName($author);
            $commentEntity->setContent($comment);
        }

        return $commentEntity;
    }

    /**
     * @return array of input fields error messages
     */
    public function getValidationErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param int $blogId
     */
    private function validateBlogId(int $blogId): void
    {
        if (!filter_var($blogId, FILTER_VALIDATE_INT)) {
            $this->isValid = false;
        }
    }

    /**
     * @param string $author
     */
    private function validateAuthor(string $author): void
    {
        if (!$author) {
            $this->errors['author'] = 'Author field is required';
            $this->isValid = false;
        } else {
            $authorLength = strlen($this->validateInput($author));
            if ($authorLength < 3 || $authorLength > 50) {
                $this->errors['author'] = 'Author name must contain between 3 - 50 characters';
                $this->isValid = false;
            }
        }
    }

    /**
     * @param string $comment
     */
    private function validateComment(string $comment): void
    {
        $comment = $this->validateInput($comment);
        if (!$comment || strlen($comment) < 3) {
            $this->errors['comment'] = 'Comment must have at least 3 characters';
            $this->isValid = false;
        }
    }

    /**
     * Returns a clean string we got from input fields of form
     *
     * @param string $string
     * @return string
     */
    private function validateInput(string $string): string
    {
        $string = trim($string);
        $string = addslashes($string);
        $string = htmlspecialchars($string);

        return $string;
    }
}