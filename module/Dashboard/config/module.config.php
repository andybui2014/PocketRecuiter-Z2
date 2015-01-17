<?php
return array(
    'router' => array(
        'routes' => array(
            'dashboard' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/dashboard[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Dashboard/Controller/dashboard',
                        'action'     => 'index',
                    ),//'defaults'
                ),//'options'
            ),//'login'
        ),//'routes'
    ), //'router'
    

    'controllers' => array(
        'invokables' => array(
            'Dashboard\Controller\dashboard' => 'Dashboard\Controller\DashboardController'
        ),
    ),

    'view_manager' => array(       
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../../Application/view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
