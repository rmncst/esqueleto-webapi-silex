<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 01/09/17
 * Time: 09:55
 */

namespace Controller;


use Application\Exception\ExpiredAccessTokenException;
use Application\Exception\FieldNotFoundException;
use Application\Exception\InvalidTokenException;
use Security\SecurityApp;
use Symfony\Component\HttpFoundation\Request;

class AuthenticateController extends ControllerBase
{
    public function auth(Request $request)
    {
        $post = $request->request->all();
        $response = [];

        if(empty($post['user']))
        {
            throw new FieldNotFoundException(['user'],'Campos obrigatórios não encontrados');
        }
        if(empty($post['password']))
        {
            throw new FieldNotFoundException(['password'],'Campos obrigatórios não encontrados');
        }

        if($post['user'] == 'root' && $post['password'] == 'pass')
        {
            $response['access_token'] = SecurityApp::encodeJasonWebToken(['user' => 'root']);
            $response['refresh_token'] = SecurityApp::encodeRefreshToken($response['access_token'],['user' => 'root']);

            //$response['decoded_access_token'] = SecurityApp::decodeJasonWebToken($response['access_token']);
            //$response['decoded_refresh_token'] = SecurityApp::decodeJasonWebToken($response['refresh_token']);
        }

        return $this->jsonResponse($response);
    }

    public function refresh_token(Request $request)
    {
        $post = $request->request->all();
        $response = [];

        if(empty($post['refresh_token']))
        {
            throw new FieldNotFoundException(['user'],'Campos obrigatórios não encontrados');
        }

        if(!SecurityApp::verifyRefreshJasonWebToken($post['refresh_token']))
        {
            if(SecurityApp::isExpiredRefreshToken($post['refresh_token']))
            {
                throw new ExpiredAccessTokenException('refresh token expired');
            }
            else
            {
                throw new InvalidTokenException('invalid refresh token in the request body !');
            }
        }

        $token = SecurityApp::decodeRefreshJasonWebToken($post['refresh_token']);

        $response['access_token'] = SecurityApp::encodeJasonWebToken(['user' => $token['user']]);
        $response['refresh_token'] = SecurityApp::encodeRefreshToken($response['access_token'],['user' => $token['user']]);


        return $this->jsonResponse($response);
    }
}