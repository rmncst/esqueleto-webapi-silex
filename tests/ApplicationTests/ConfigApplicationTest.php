<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 15:49
 */

namespace Test\ApplicationTests;


use Application\Commom\ConfigApplication;
use Application\Provider\ControllerServiceProvider;
use Security\SecurityApp;

class ConfigApplicationTest extends \PHPUnit\Framework\TestCase
{
    public function testControllerServiceProvider()
    {
        $service = new ControllerServiceProvider();

        $this->assertContains('HomeController.php',$service->getControllersDirectoryPath());
        $this->assertEquals('home',$service->normalizeNameController('HomeController.php'));
    }

    public function testRoutesFromFile()
    {
        $routes = ConfigApplication::getRoutesArray();
        $this->assertContains('routes',array_keys($routes));
    }

    public function testJWT()
    {
        $token = SecurityApp::encodeJasonWebToken(['foo'=>'bar']);

        list($headb64, $bodyb64, $cryptob64) = explode('.', $token);

        $payload = base64_decode($bodyb64);

        print PHP_EOL;
        print_r(base64_decode($cryptob64));

        $routes = ConfigApplication::getRoutesArray();
        $this->assertContains('routes',array_keys($routes));
    }
}