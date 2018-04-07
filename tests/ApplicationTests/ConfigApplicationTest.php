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
use Test\BaseWebTest;

class ConfigApplicationTest extends BaseWebTest
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

}