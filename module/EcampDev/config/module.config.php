<?php
return array(
	
	'ecamp' => array(),
		
    'router' => array(
        'routes' => array(
        	'dev' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/dev',
                    'defaults' => array(
                        '__NAMESPACE__' => 'EcampDev\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                        ),
                    ),
                ),
            ),
		),
	),
	
		
    'controllers' => array(
        'invokables' => array(
            'EcampDev\Controller\Service' 		=> 'EcampDev\Controller\ServiceController',
            'EcampDev\Controller\Repository'	=> 'EcampDev\Controller\RepositoryController',
        ),
    ),
    
	'view_manager' => array(
        'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),	
);