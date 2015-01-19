<?php
return array(
    'router' => array(
        'routes' => array(
            'candidate' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/candidate[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Candidate/Controller/candidate',
                        'action'     => 'startProfile',
                    ),//'defaults'
                ),//'options'
            ),//'login'
        ),//'routes'
    ), //'router'
    

    'controllers' => array(
        'invokables' => array(
            'Candidate/Controller/candidate' => 'Candidate\Controller\CandidateController'
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
