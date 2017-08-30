<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 15:42
 */

namespace Application\Provider;

use Application\Commom\ConfigApplication;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ControllerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        foreach ($this->getControllersDirectoryPath() as $controller)
        {
            $controllerName = $this->normalizeNameController($controller);
            if($controllerName != "")
            {
                $controller = 'Controller\\'.str_replace('.php','',$controller);
                $app['controller.'.$controllerName] = function () use($app,$controller){
                    return new $controller($app);
                };
            }
        }
    }

    public function getControllersDirectoryPath()
    {
        $data = scandir(ConfigApplication::getControllerRootDirectory());
        return $data;
    }

    public function normalizeNameController($entry)
    {
        $newname = str_replace('.php','',strtolower($entry));
        $newname = str_replace('controller','',$newname);
        $newname = str_replace('.','',$newname);

        return $newname;
    }
}