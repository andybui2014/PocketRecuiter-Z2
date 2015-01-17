<?php
return array(
    'router' => array(
        'routes' => array(
            'notifications' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/notifications[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Notifications/Controller/notifications'
                    ),//'defaults'
                ),//'options'
            ),//'login'
        ),//'routes'
    ), //'router'
    

    'controllers' => array(
        'invokables' => array(
            'Notifications/Controller/notifications' => 'Notifications\Controller\NotificationsController'
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
