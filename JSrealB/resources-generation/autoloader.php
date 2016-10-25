<?php

/**
 * Description of autoloader
 *
 * @author molinspa
 */

$loglevel = 1;
function lg($level, $message = "M", $obj = "")
{
    global $loglevel;
	if($level <= $loglevel)
		{
			echo $message ;// . $obj . "<br />";
			var_dump($obj);
			echo "<br /><br />";
		}
}

class AutoLoader
{
    static private $classNames = array();

    /**
     * Store the filename (sans extension) & full path of all ".php" files found
     */
    public static function registerDirectory($dirName)
    {

        $di = new DirectoryIterator($dirName);
        foreach($di as $file)
        {

            if($file->isDir() && !$file->isLink() && !$file->isDot())
            {
                // recurse into directories other than a few special ones
                self::registerDirectory($file->getPathname());
            }
            elseif(substr($file->getFilename(), -4) === '.php')
            {
                // save the class name / path of a .php file found
                $className = substr($file->getFilename(), 0, -4);
                AutoLoader::registerClass($className, $file->getPathname());
            }
        }
    }

    public static function registerClass($className, $fileName)
    {
        AutoLoader::$classNames[$className] = $fileName;
    }

    public static function loadClass($className)
    {
        $properClassName = trim($className);
        if(isset(AutoLoader::$classNames[$properClassName]))
        {
//            echo AutoLoader::$classNames[$className] . '<br />';
            require_once(AutoLoader::$classNames[$properClassName]);
        }
    }
}

spl_autoload_register(array('AutoLoader', 'loadClass'));

AutoLoader::registerDirectory(REAL_PATH_ROOT . 'config/');
AutoLoader::registerDirectory(REAL_PATH_ROOT . 'app/');