<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 31/08/17
 * Time: 15:57
 */

namespace Test\ApplicationTests;

use Security\SecurityApp;
use Test\BaseWebTest;

class JWTGenerationTest extends BaseWebTest
{

    public function testGenerateToken()
    {
        $token = SecurityApp::encodeJasonWebToken(['user' => 'foo']);
        $refresh_token = SecurityApp::encodeRefreshToken($token,['user' => 'foo']);

        $this->assertTrue(SecurityApp::verifyJasonWebToken($token));

        sleep(20);

        $this->assertTrue(SecurityApp::verifyJasonWebToken($token));
        $this->assertFalse(SecurityApp::verifyRefreshJasonWebToken($refresh_token));

        sleep(10);

        $this->assertFalse(SecurityApp::verifyJasonWebToken($token));
        $this->assertTrue(SecurityApp::verifyRefreshJasonWebToken($refresh_token));
    }


//
//    public function testJWT()
//    {
//        $token = SecurityApp::encodeJasonWebToken(['foo'=>'bar']);
//        $result = SecurityApp::verifyJasonWebToken($token);
//
//        $this->assertTrue($result);
//
//        $payload = SecurityApp::decodeJasonWebToken($token);
//
//        $this->assertContains('bar',$payload);
//    }
//
//    public function testErrorJWT()
//    {
//        $token = SecurityApp::encodeJasonWebToken(['foo'=>'bar'],time() - 10);
//        $result = SecurityApp::verifyJasonWebToken($token);
//
//        $this->assertFalse($result);
//        $this->assertTrue(SecurityApp::isExpiredToken($token));
//    }


}