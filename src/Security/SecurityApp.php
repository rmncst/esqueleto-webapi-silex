<?php
namespace Security;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Application;
use Application\Commom\ConfigApplication;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SecurityApp
 *
 * @author rmncst
 */
class SecurityApp 
{    
    const REFRESH_KEY = 'example_key';
    const REFRESH_ALGO = 'HS256';
    const ALGO = 'RS256';
    const TIME_EXP = 30;

    
    public static function encodeJasonWebToken(array $token, $exp = null, $nbf = null)
    {
        $token = array_merge($token,self::getDefaultClamsJWT());

        if($exp !== null)
        {
            $token['exp'] = $exp;
        }

        if($nbf !== null)
        {
            $token['nbf'] = $nbf;
        }

        $jwt = JWT::encode($token,ConfigApplication::getPrivateKey(),self::ALGO);
        return $jwt;       
    }

    public static function encodeRefreshToken($jwt, array $data)
    {
        $token = self::decodeJasonWebToken($jwt);
        $token['exp'] += 3 * self::TIME_EXP;
        $token['nbf'] = $token['exp'];
        $token['iat'] = time();

        return JWT::encode(array_merge($token,$data),self::REFRESH_KEY,self::REFRESH_ALGO);
    }


    public static function decodeJasonWebToken($token)
    {
        JWT::$leeway = '60';
        $jwt = JWT::decode($token, ConfigApplication::getPublicKey(),[self::ALGO]);
        return (array) $jwt;
    }

    public static function decodeRefreshJasonWebToken($token)
    {
        $jwt = JWT::decode($token, self::REFRESH_KEY,[self::REFRESH_ALGO]);
        return (array) $jwt;
    }

    public static function getDefaultClamsJWT()
    {
        $token = array(
            "iss" => "http://www.domain.dom.br",
            "aud" => "http://www.domain.dom.br",
            "iat" => time(),
            "nbf" => time(),
            "exp" => time() + self::TIME_EXP
        );

        return $token;
    }


    public static function verifyJasonWebToken($token)
    {
        try
        {
            JWT::decode($token, ConfigApplication::getPublicKey() , array(self::ALGO));
            return true;
        }
        catch (\Exception $ex)
        {
            return false;
        }
    }

    public static function verifyRefreshJasonWebToken($token)
    {
        try
        {
            JWT::decode($token, self::REFRESH_KEY , array(self::REFRESH_ALGO));
            return true;
        }
        catch (\Exception $ex)
        {
            return false;
        }
    }

    public static function isExpiredToken($token)
    {
        try
        {
            self::decodeJasonWebToken($token);
        }
        catch (ExpiredException $exception)
        {
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }

        return false;
    }


    public static function isExpiredRefreshToken($token)
    {
        try
        {
            self::decodeRefreshJasonWebToken($token);
        }
        catch (ExpiredException $exception)
        {
            return true;
        }
        catch (\Exception $exception)
        {
            return false;
        }

        return false;
    }


    public static function encodePassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public static function verifyPassword($passord)
    {
        return password_verify($passord, PASSWORD_BCRYPT);
    }
}
