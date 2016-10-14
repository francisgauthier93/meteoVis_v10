<?php

/**
 * Description of Router
 *
 * @author molinspa
 */
class Router
{
    private $registry;
    
    private $path;
    private $args = array();
    public $file;
    public $controller;
    public $action = array();

    public function __construct($registry)
    {
        $this->registry = $registry;
    }
    
    public function setRealRootPath($path)
    {
        /*         * * check if path i sa directory ** */
        if(is_dir($path) == false)
        {
            throw new Exception('Invalid controller path: `' . $path . '`');
        }
        /*         * * set the path ** */
        $this->path = $path;
    }
    
    public function getRealRootPath()
    {
        return $this->path;
    }
    
    public function loader()
    {
        $this->getController();

        // Get Controller
        $class = ucfirst($this->controller) . 'Controller';
        $controller = new $class($this->registry);
        $controller->setUp();
        
        // Run
        foreach($this->action as $sAction)
        {
            if(is_callable(array($controller, $sAction)) == false)
            {
                $controller->notFound();
                break;
            }
            else
            {
                $controller->$sAction();
            }
        }
    }
    
    private function getController()
    {
        $route = (Superglobal::isGetKey('c')) ? Superglobal::getGetValueByKey('c') : null;

        if(empty($route))
        {
            $route = 'index';
        }
        else
        {
            $route = 'index';
        }

        if(empty($this->controller))
        {
            $this->controller = 'meteo';
        }

        $this->action[] = 'header';
        if($route ===  'index')
        {
            $this->action[] = 'weather';
            $this->action[] = 'graph';
            $this->action[] = 'forecast';
        }
        $this->action[] = 'footer';
    }
}