<?php
namespace Login\Model;
use Zend\Session\Container;

class prSession {
   // const SESSION_CLIENT = "SESSION_CLIENT"; 
    const SESSION_USER = "SESSION_USER";    
    //static $session_user;
    
    function __construct() {
        //Zend_Session::start();
    }

   // static function setSession($data, $key = "SESSION_CLIENT") {
        static function setSession($data, $key = "SESSION_USER") {
        if(!empty($data))
        {
            $session = new Container('MyAppName');
            $session->$key = $data;
        }             
    }
   // static function getSession($key = "SESSION_CLIENT") {
   //
    static function getSession($key = "SESSION_USER") {
        
        $session = new Container('MyAppName');
        return $session->$key;
    }
    
   // static function clearSession($key = "SESSION_CLIENT") {
    static function clearSession($key = "SESSION_USER") {
        $session = new Container('MyAppName');
        $session->offsetUnset($key);
    }
    
    static function clearSessions() {
        $session = new Container('MyAppName');
        $session->getManager()->getStorage()->clear();
        $session->SESSION_USER=null;
    }
}  

