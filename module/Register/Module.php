<?php
namespace Register;
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
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Register\Model\SendMail' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\SendMail($dbAdapter);
                    return $table;
                },
				'Register\Model\UserTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\UserTable($dbAdapter);
                    return $table;
                },
                'Register\Model\CompanyTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\CompanyTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
     
}	
