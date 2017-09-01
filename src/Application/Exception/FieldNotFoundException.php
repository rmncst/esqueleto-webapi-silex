<?php
/**
 * Created by PhpStorm.
 * User: rmncst
 * Date: 01/09/17
 * Time: 10:00
 */

namespace Application\Exception;


use Throwable;

class FieldNotFoundException extends \Exception
{
    private $fields_not_found = array();

    public function __construct(array $fields, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->fields_not_found = $fields;
    }


    /**
     * @return array
     */
    public function getFieldsNotFound()
    {
        return $this->fields_not_found;
    }

    /**
     * @param string $field
     */
    public function setFieldsNotFound($field)
    {
        $this->fields_not_found[] = $field;
    }



}