<?php

namespace Controller;
use Application\CustomResponse\JsonCustomResponse;
use Silex\Application;

/**
 * Description of ControllerBase
 *
 * @author rmncst
 */
class ControllerBase 
{
    /**
     *
     * @var Application
     */
    protected $_app;
    
    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $_repo;
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;


    public function __construct(Application $app) {
        $this->_app = $app;
        $this->_em = $app['em'];
    }
    
    public function setRepository($repo)
    {
        $this->_repo = $this->_em->getRepository($repo);
    }

    public function jsonResponse(array $body, $message = null, $status = JsonCustomResponse::STATUS_OK)
    {
        $response = new JsonCustomResponse($body,$message,$status);
        return $response;
    }
}
