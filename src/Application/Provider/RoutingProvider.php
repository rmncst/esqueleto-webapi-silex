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

        //$app->get('/',function () { return 'lol'; });

        $this->CrudRoutes($app, 'controller.post', "/post");
        $this->CrudRoutes($app, 'controller.comentario', "/comentario");
        
        $app->get('/comentario/post/{postId}','controller.comentario:getAllByPost');
    }
    
    public function CrudRoutes(\Pimple\Container $app , $controller, $prefix)
    {
        $app->get($prefix.'/{id}',$controller.':get');
        $app->get($prefix , $controller.':getAll');
        $app->post($prefix ,$controller.':add');
        $app->put($prefix, $controller.':update');
        $app->delete($prefix, $controller.':delete');
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
