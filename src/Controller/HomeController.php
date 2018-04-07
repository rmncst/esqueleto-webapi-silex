<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 15:30
 */

namespace Controller;


use Application\CustomResponse\JsonCustomResponse;

class HomeController
{
    public function index()
    {
        return ["Hello World!"];
    }

    public function json()
    {
        return new JsonCustomResponse();
    }
}