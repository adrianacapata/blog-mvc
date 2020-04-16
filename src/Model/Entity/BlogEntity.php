<?php


namespace Blog\Model\Entity;


class BlogEntity
{
    /** @var int */
    private $id;

    /** @var int*/
    private $categoryId;

    /** @var string */
    private $author_name;

    /** @var string */
    private $title;

    /** @var string */
    private $content;

    /** @var     */
    private $created_at;

    /** @var int */
    private $status;

    /** @var int */
    private $likeCount;

    /** @var int */
    private $dislikeCount;

    /** @var     */
    private $updatedAt;

    /** @var int */
    private $views;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    /**
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->author_name;
    }

    /**
     * @param string $author_name
     */
    public function setAuthorName(string $author_name): void
    {
        $this->author_name = $author_name;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param mixed $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    /**
     * @param int $likesCount
     */
    public function setLikesCount(int $likesCount): void
    {
        $this->likesCount = $likesCount;
    }

    /**
     * @return int
     */
    public function getDislikeCount(): int
    {
        return $this->dislikeCount;
    }

    /**
     * @param int $dislikeCount
     */
    public function setDislikeCount(int $dislikeCount): void
    {
        $this->dislikeCount = $dislikeCount;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }
}