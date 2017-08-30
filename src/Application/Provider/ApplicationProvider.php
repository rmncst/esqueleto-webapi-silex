<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 15:31
 */

namespace Application\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Provider\ServiceControllerServiceProvider;

class ApplicationProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->register(new DoctrineOrmProvider());
        $app->register(new ServiceControllerServiceProvider());
        $app->register(new RoutingProvider());
        $app->register(new ControllerServiceProvider());
        $app->register(new CustomResponseProvider());
    }
}