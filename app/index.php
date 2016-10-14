<?php

ob_start();

try
{
    $oRegistry = new Registry();

     /*** load the router ***/
    $oRegistry->router = new Router($oRegistry);

    /*** set the controller path ***/
    $oRegistry->router->setRealRootPath(Config::get('path.real.root'));

    /*** load up the template ***/
    $oRegistry->template = new Template($oRegistry);

    /*** load the controller ***/
    $oRegistry->router->loader();

    //Util::var_dump($oRegistry->router); die();
}
catch(Exception $e)
{
    ob_end_clean();
    ob_start();
    echo 'Erreur : ' . $e->getMessage() . '<br />';
    
    if(Config::isDevEnv())
    {
        echo 'Trace : ' . $e->getTraceAsString();
    }
}

$sContent = ob_get_contents();

ob_end_clean();

echo $sContent;