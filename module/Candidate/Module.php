<?php
namespace Candidate;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Candidate\Model\prApiCoreCandidateClass;
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
                'Candidate\Model\prApiCoreCandidateClass' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new prApiCoreCandidateClass($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}
