<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 15:36
 */

namespace Application\Provider;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ErrorProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $app)
    {
        $app->error(function(\Exception $erro){
            return new Response('Oops, erro: '. $erro->getMessage(),500);
        });

        $app->error(function (SecurityException $erro){
            return new Response('Você é um intruso, '. $erro->getMessage(),404);
        });
    }
}