<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 31/08/17
 * Time: 15:57
 */

namespace Test\ApplicationTests;


use Security\SecurityApp;

class JWTGenerationTest extends \PHPUnit\Framework\TestCase
{

    public function testJWT()
    {
        $token = SecurityApp::encodeJasonWebToken(['foo'=>'bar']);
        $result = SecurityApp::verifyJasonWebToken($token);

        $this->assertTrue($result);

        $payload = SecurityApp::decodeJasonWebToken($token);

        $this->assertContains('bar',$payload);
    }

    public function testErrorJWT()
    {
        $token = SecurityApp::encodeJasonWebToken(['foo'=>'bar'],time() - 10);
        $result = SecurityApp::verifyJasonWebToken($token);

        $this->assertFalse($result);
        $this->assertTrue(SecurityApp::isExpiredToken($token));
    }
}