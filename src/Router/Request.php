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

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * @return array
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->actionName;
    }

    // .... public methods

    private function initControllerAction(): void
    {
        preg_match_all('/(?:\/)(\w+)/', $this->url, $urlElements);

        $this->controllerName = $urlElements[1][0] ?? null;

        $this->actionName = empty($urlElements[1][1]) ? 'index' : $urlElements[1][1];
    }
}