<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 17:05
 */

namespace Application\Provider;


use Application\CustomResponse\JsonCustomResponse;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CustomResponseProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->view(function (JsonCustomResponse $response) use($app){
            return $app->json($response);
        });


        $app->error(function(\Exception $erro) use ($app){
            $errorResponse = new JsonCustomResponse(['error_exception' => $erro->getMessage()],
                'Erro inesperado',
                JsonCustomResponse::STATUS_ERROR);

            return $app->json($errorResponse,500);
        });


    }

}