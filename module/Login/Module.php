<?php
namespace Login;
use Login\Model\userLogin;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
class Module
{

public function getAutoloaderConfig()
{
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

public function getConfig()
{
return include __DIR__ . '/config/module.config.php';
}

public function getServiceConfig()
{
    return array(
        'factories' => array(
            'Login\Model\userLogin' => function($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                $table = new userLogin($dbAdapter);
                return $table;
            },
        ),
    );
}
}


