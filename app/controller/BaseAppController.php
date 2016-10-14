<?php

/**
 * Description of BaseAppController
 *
 * @author molinspa
 */
abstract class BaseAppController
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }
    
    abstract public function setUp();
    
    public function header()
    {
        $this->registry->template->show('header');
    }
    
    public function footer()
    {
        $this->registry->template->show('footer');
    }
    
    public function notFound()
    {
        $this->registry->template->show('not_found');
    }
}