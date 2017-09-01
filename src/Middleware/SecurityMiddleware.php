<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 30/08/17
 * Time: 11:50
 */

namespace Middleware;


use Application\CustomResponse\JsonCustomResponse;
use Application\Exception\AccessTokenNotFoundException;
use Application\Exception\ExpiredAccessTokenException;
use Application\Exception\InvalidTokenException;
use Security\SecurityApp;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class SecurityMiddleware
{
    public static function auth(Request $request, Application $app)
    {
        $authorization = $request->headers->get('Authorization');

        if(stristr($request->getUri(), '/auth/'))
        {
            return;
        }

        if($authorization == null)
        {
            throw new AccessTokenNotFoundException('access token not found in the request header!');
        }

        if(!SecurityApp::verifyJasonWebToken($authorization))
        {
            if(SecurityApp::isExpiredToken($authorization))
            {
                throw new ExpiredAccessTokenException('access token expired');
            }
            else
            {
                throw new InvalidTokenException('invalid access token in the request header !');
            }
        }

        return null;
    }

}