<?php
/**
 * @author Lorand Tamas <lta@newpharma.ro>
 * @since 4/8/20 12:48 PM
 */
namespace Blog\Router;

/**
 * Class Request
 * @package Blog\Router
 */
class Request
{
    private static $instance;

    private $url;
    private $queryParameters;
    private $postParameters;
    private $files;
    // ...

    /** @var string */
    private $controllerName;
    /** @var string */
    private $actionName;

    private function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->queryParameters = $_GET;
        $this->postParameters = $_POST;
        $this->files = $_FILES;
        // ...

        $this->initControllerAction();
    }

    /**
     * @return Request
     */
    public static function getInstance(): Request
    {
        if (!isset(self::$instance)) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    public function getBaseUrl()
    {
        return parse_url($this->url, PHP_URL_PATH);
    }
    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    public function getPostParameters(): array
    {
        return $this->postParameters;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    public function getActionName(): string
    {
        return $this->actionName;
    }

    private function initControllerAction(): void
    {
        preg_match_all('/(?:\/)(\w+)/', $this->url, $urlElements);

        $this->controllerName = ucfirst($urlElements[1][0]);

        $this->actionName = empty($urlElements[1][1]) ? 'index' : $urlElements[1][1];
    }
}