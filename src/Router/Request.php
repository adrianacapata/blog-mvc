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
    public static function getInstance()
    {
            if (!isset(Request::$instance)) {
                Request::$instance = new Request();
            }

            return Request::$instance;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    /**
     * @return mixed
     */
    public function getPostParameters()
    {
        return $this->postParameters;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    // .... public methods

    private function initControllerAction()
    {
        $urlElements = explode('/', $this->url);

        $this->controllerName = $urlElements[1];

        $this->actionName = empty($urlElements[2]) ? 'index' : $urlElements[2];
    }
}