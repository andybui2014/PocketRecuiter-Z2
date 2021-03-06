<?php
namespace Notifications;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Notifications\Model\prApiCoreNotiClass;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
         );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Notifications\Model\prApiCoreNotiClass' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new prApiCoreNotiClass($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}
