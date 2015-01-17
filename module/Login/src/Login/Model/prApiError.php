<?php

namespace Login\Model;

class prApiError
{
     static private $instance = null;

    protected $table = null;
    private $errors = array();
    private $index = array();
    private $standardErrors = array(1 => 'External SOAP error',
        2 => 'Required fields are not set',
        3 => 'Authenticate is failed',
        4 => 'Not HTTPS protocol used',
        5 => 'Not enough permissions',
        6 => 'Validating error',
        7 => 'SQL error');

    private function __construct()
    {

    }

    private function __clone(){}

    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new prApiError();

        return self::$instance;
    }

    public function hasErrors()
    {
        return (bool) sizeof($this->errors);
    }

    private function makeStandardErrors()
    {
    }

    public function addError($errorId, $description= null)
    {
        //$this->makeStandardErrors();
        $error['description'] = $description;
        $error['error_id'] = $errorId;
        $error['id'] = $this->standardErrors[$errorId];
        $this->errors[] = $error;
    }

    public function addErrors($errorId, $errors)
    {
        foreach ($errors as $val) {
            $error['description'] = $val;
            $error['error_id'] = $errorId;
            $error['id'] = $this->standardErrors[$errorId];
            $this->errors[] = $error;
        }
    }

    public function getErrorArray() {
        return $this->errors;
    }

}
