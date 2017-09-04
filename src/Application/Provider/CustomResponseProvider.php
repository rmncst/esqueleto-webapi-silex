<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 17:05
 */

namespace Application\Provider;


use Application\CustomResponse\JsonCustomResponse;
use Application\Exception\AccessTokenNotFoundException;
use Application\Exception\ExpiredAccessTokenException;
use Application\Exception\FieldNotFoundException;
use Application\Exception\InvalidTokenException;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class CustomResponseProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->view(function (JsonCustomResponse $response) use($app){
            return $app->json($response);
        });

        $app->error(function (AccessTokenNotFoundException $exception) use ($app){
            $error = new JsonCustomResponse(
                ['error' => $exception->getMessage()],
                'Acesso não autorizado',
                JsonCustomResponse::STATUS_ERROR
            );

            return $app->json($error,401);
        });


        $app->error(function (ExpiredAccessTokenException $exception) use ($app){
            $error = new JsonCustomResponse(
                ['error' => $exception->getMessage()],
                'Acesso não autorizado',
                JsonCustomResponse::STATUS_ERROR
            );

            return $app->json($error,404);
        });

        $app->error(function (InvalidTokenException $exception) use ($app){
            $error = new JsonCustomResponse(
                ['error' => $exception->getMessage()],
                'Acesso não autorizado',
                JsonCustomResponse::STATUS_ERROR
            );

            return $app->json($error,400);
        });

        $app->error(function (FieldNotFoundException $exception) use ($app){
            $message = 'Os seguintes campos obrigatórios não foram encontrados no corpor da requisição: ';
            $message .= implode(", ",$exception->getFieldsNotFound());
            $errorResponse = new JsonCustomResponse(['error_exception' => $message],
                $exception->getMessage(),JsonCustomResponse::STATUS_ERROR);

            return $app->json($errorResponse,500);
        });

        $app->error(function(\Exception $erro) use ($app){
            $errorResponse = new JsonCustomResponse(['error_exception' => $erro->getMessage() ,'stack' => $erro->getTraceAsString()],
                'Erro inesperado',
                JsonCustomResponse::STATUS_ERROR);

            return $app->json($errorResponse,500);
        });
    }

}