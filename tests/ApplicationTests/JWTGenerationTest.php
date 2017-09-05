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
        $client = $this->getClientCrawler();

        $client->request('POST','/auth/sign-up',['user' => 'root', 'password' => 'pass']);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $content = (array) json_decode($response->getContent(),true);

        $this->assertContains('access_token', array_keys($content['body']));
        $this->assertContains('refresh_token', array_keys($content['body']));

        return $content;
    }

    /**
     * @depends testGenerateToken
     */
    public function testCallPrivateRoute(array $content)
    {
        $client = $this->getClientCrawler();

        $token = $content['body']['access_token'];

        // Chamando uma rota privada

        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('body',array_keys((array)json_decode($client->getResponse()->getContent())));

        return $content;
    }

    /**
     * @depends testCallPrivateRoute
     */
    public function testExpireAccessToken(array $content)
    {
        $client = $this->getClientCrawler();

        $token = $content['body']['access_token'];

        sleep(30);
        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $response = $client->getResponse();
        $this->assertTrue(!$response->isOk());
        $this->assertEquals(404, $response->getStatusCode());

        return $content;
    }

    /**
     * @depends testExpireAccessToken
     */
    public function testRefreshToken(array $content)
    {
        $client = $this->getClientCrawler();

        $refresh_token = $content['body']['refresh_token'];

        $client->request('POST','/auth/refresh-token',array('refresh_token' => $refresh_token));
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $content = (array) json_decode($response->getContent(),true);
        $token = $content['body']['access_token'];

        // fazendo a request com o novo token

        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());
    }

}