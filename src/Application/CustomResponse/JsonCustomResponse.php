<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 28/08/17
 * Time: 17:04
 */

namespace Application\CustomResponse;


class JsonCustomResponse implements \JsonSerializable
{
    private $length;

    private $body;

    private $message;

    private $status;

    const STATUS_OK = 0;
    const STATUS_WARNING = 2;
    const STATUS_INFO = 1;
    const STATUS_ERROR = 3;


    public function __construct(array $body = [],$message = null,$status = 0)
    {
        $this->setArrayBody($body);
        $this->setMessage($message);
        $this->setStatus($status);
    }

    /**
     * @param mixed $body
     */
    public function setArrayBody(array $body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $body
     */
    public function setObjectBody(\JsonSerializable $body)
    {
        $this->body = $body;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }


    function jsonSerialize()
    {
        return ['status' => $this->status ,
            'message' => $this->message,
            'length' => count($this->body),
            'body' => $this->body ];
    }
}