<?php

namespace Test;
use Application\Provider\ApplicationProvider;
use Curl\Curl;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\HttpKernel\Client;

/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 04/09/17
 * Time: 10:25
 */
class BaseWebTest extends \PHPUnit\Framework\TestCase
{

    public function createApplication()
    {
        $app = new Application();
        $app->register(new ApplicationProvider());

        return $app;
    }

    public function getClientCrawler()
    {
        $client = new Client($this->createApplication(), array());
        return $client;
    }

    public function testCreateApplication()
    {
        $client = $this->getClientCrawler();

        $crawler = $client->request('GET', '/');
        $this->assertCount(1,$crawler->filter('body:contains("Hellow World")'));
        $this->assertTrue($client->getResponse()->isOk());
    }


    public function testIntegrationAPIAuth()
    {
        $client = $this->getClientCrawler();

        // Autenticando com usuário e senha...

        $client->request('POST','/auth/sign-up',['user' => 'root', 'password' => 'pass']);
        $response = $client->getResponse();

        $this->assertTrue($response->isOk());
        $content = (array) json_decode($response->getContent(),true);

        $this->assertContains('access_token', array_keys($content['body']));
        $this->assertContains('refresh_token', array_keys($content['body']));

        $token = $content['body']['access_token'];
        $refresh_token = $content['body']['refresh_token'];

        // Chamando uma rota privada

        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $this->assertTrue($client->getResponse()->isOk());
        $this->assertContains('body',array_keys((array)json_decode($client->getResponse()->getContent())));

        // forçando o token expirar

        sleep(30);

        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $response = $client->getResponse();
        $this->assertTrue(!$response->isOk());
        $this->assertEquals(404, $response->getStatusCode());

        // refresh token

        $client->request('POST','/auth/refresh-token',array('refresh_token' => $refresh_token));
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $content = (array) json_decode($response->getContent(),true);

        $token = $content['body']['access_token'];
        $refresh_token = $content['body']['access_token'];

        // fazendo a request com o novo token

        $client->request('GET','/usuario',array(),array(),['HTTP_Authorization' => $token]);
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

    }
}