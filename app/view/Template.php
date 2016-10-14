<?php

/**
 * Description of Template
 *
 * @author molinspa
 */
class Template
{
    private $registry;
    private $vars = array();

    function __construct($registry)
    {
        $this->registry = $registry;
    }
    
    public function __set($index, $value)
    {
        $this->vars[$index] = $value;
    }

    function show($name)
    {
        $path = $this->registry->router->getRealRootPath() . 'app/template/' . $name . '.php';

        if(file_exists($path) == false)
        {
            throw new TemplateNotFoundException('Template not found in ' . $path);
        }

        // Load view variables
        foreach($this->vars as $key => $value)
        {
            $$key = $value;
        }
        
        require_once ($path);
    }
}