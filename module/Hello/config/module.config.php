
<?php
return array(
'controllers' => array(
'invokables' => array(
'Hello\Controller\Hello' => 'Hello\Controller\HelloController',
),
),

// The following section is new and should be added to your file
'router' => array(
'routes' => array(
'hello' => array(
'type' => 'segment',
'options' => array(
'route' => '/hello[/:action][/:id]',
'constraints' => array(
'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
'id' => '[0-9]+',
),
'defaults' => array(
'controller' => 'Hello\Controller\Hello',
'action' => 'index',
),
),
),
),
),	

'view_manager' => array(
'template_path_stack' => array(
'hello' => __DIR__ . '/../view',
),
),
);