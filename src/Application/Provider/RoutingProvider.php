<?php

namespace Application\Provider;
use Application\Commom\ConfigApplication;
use Middleware\SecurityMiddleware;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of RoutingProvider
 *
 * @author rmncst
 */
class RoutingProvider implements \Pimple\ServiceProviderInterface 
{    
    
    public function register(\Pimple\Container $app) 
    {
        $this->registerRoutesFromFile($app);
    }

    public function registerRoutesFromFile(Application $app)
    {
        $routes = ConfigApplication::getRoutesArray();

        foreach ($routes['routes'] as $key => $val)
        {
            $app->match($val['path_uri'],$val['action'])
                ->method($val['method'])
                ->bind($key);
        }

        foreach ($routes['routes_grouped'] as $key => $val)
        {
            $prefix = $val['prefix'];
            $name = $key;

            foreach ($val['routes'] as $subkey => $subval)
            {
                $app->match($prefix.''.$subval['path_uri'], $subval['action'])
                    ->method($subval['method'])
                    ->before(function (Request $request, Application $app){
                        SecurityMiddleware::auth($request,$app);
                    })
                    ->bind($name.'_'.$subkey);
            }
        }
    }

    

}
