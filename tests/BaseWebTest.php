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
class BaseWebTest extends WebTestCase
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
}