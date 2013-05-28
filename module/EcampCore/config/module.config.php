<?php
return array(
	'ecamp' => array(
		'modules' => array(
			'ecampcore' => array(
				'repos' => array(
					'module_namespace' 	=> 'EcampCore',
					'config_file' 		=> __DIR__ . '/service.config.repos.php',
					'traits_path' 		=> __DIR__ . '/../src/EcampCore/RepositoryTraits/',
					'traits_namespace'	=> 'EcampCore\RepositoryTraits'
				),
				
				'services' => array(
					'services_path' 	=> __DIR__ . '/../src/EcampCore/Service/',
					'config_file' 		=> __DIR__ . '/service.config.services.php',
					'traits_path' 		=> __DIR__ . '/../src/EcampCore/ServiceTraits/',
					'traits_namespace'	=> 'EcampCore\ServiceTraits'
				),
			)
		),
			
		'acl' => array(
			'resources' => array(
				'EcampCore\Entity\Camp'		=>  null,
				'EcampCore\Entity\Period' 	=> 'EcampCore\Entity\Camp',
				'EcampCore\Entity\Day' 		=> 'EcampCore\Entity\Period',
				// ...
					
				'EcampCore\Entity\Group'	=> null,
				// ...
				
				'EcampCore\Entity\User'		=> null,
				// ...
			),
			
			'roles' => array(
				'guest'		=> null,
				'member'	=> 'guest',
				'admin'		=> 'member'
			),
		)
			
	),
	
    'router' => array(
        'routes' => array(
        	
            'plugin' => array(
            	'type'    => 'Segment',
            	'options' => array(
            		'route'    => '/plugin/:pluginInstanceId',
            		'constraints' => array(
            			'pluginInstanceId' => '[a-f0-9]+'
            		),
            	),
            	'may_terminate' => false,
            ),
        	
        	'core' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/core',
                    'defaults' => array(
                        '__NAMESPACE__' => 'EcampCore\Controller',
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
        	
        	'group' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/group',
        			'defaults' => array(
        				'__NAMESPACE__' => 'EcampCore\Controller',
        				'controller'    => 'Index',
        				'action'        => 'index',
        			),
        		),
        		'may_terminate' => true,
        		
        		'child_routes' => array(        		
		        	'camp' => array(
		        		'type' => 'EcampCore\Router\GroupCampRouter',
		        		'options' => array(
		        			'defaults' => array(),
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
		        			)	
		        		)
        			)
        		)
        	),
        	
        	'user' => array(
        		'type'    => 'Literal',
        		'options' => array(
        			'route'    => '/user',
        			'defaults' => array(
        				'__NAMESPACE__' => 'EcampCore\Controller',
        				'controller'    => 'Index',
        				'action'        => 'index',
        			),
        		),
        		'may_terminate' => true,
        		
        		'child_routes' => array(        		
		        	'camp' => array(
		        		'type' => 'EcampCore\Router\UserCampRouter',
		        		'options' => array(
		        			'defaults' => array(),
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
		        			)	
		        		)
        			)
        		)
        	)
		),
	),
	
		
	'd_i' => array(
		'allowed_controllers' => array(
			'EcampCore\Controller\TestController'
		),
			
		
		'instance' => array(
			
// 			'EcampCore\Controller\TestController' => array(
// 				'parameters' => array(
// 					'userRepo' => 'ecampcore.repo.user',
// 					'campRepo' => 'ecampcore.repo.camp',
// 				),
// 			),
		),
		
	),
		
    'controllers' => array(
    	'abstract_factories' => array(
    		'EcampCore\Controller\CommonControllerAbstractFactory'
    	),
    	
//         'invokables' => array(
//             'EcampCore\Controller\Index' 	=> 'EcampCore\Controller\IndexController',
//             'EcampCore\Controller\Login'	=> 'EcampCore\Controller\LoginController',
//             'EcampCore\Controller\Event'	=> 'EcampCore\Controller\EventController',
//            'EcampCore\Controller\Test'		=> 'EcampCore\Controller\TestController',
//         ),
    	'factories' => array(
    		'EcampCore\Controller\Test' => function($serviceLocator){
				$sm = $serviceLocator->getServiceLocator()->get('ServiceManager');
				return new EcampCore\Controller\TestController(
    				$sm->get('ecampcore.repo.user'),
    				$sm->get('ecampcore.repo.camp')
    			);
			},
    	),
    ),
    
	'view_manager' => array(
        'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
	
	'doctrine' => array(
		'driver' => array(
			'ecamp_entities' => array(
				'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
				'cache' => 'array',
				'paths' => array(__DIR__ . '/../src/EcampCore/Entity')
			),
			
			'orm_default' => array(
				'drivers' => array(
					'EcampCore\Entity' => 'ecamp_entities'
				)
			)
		),
		
		'configuration' => array(
			'orm_default' => array(
				'filters' => array(
					'user' 			=> 'EcampCore\DbFilter\UserFilter',
					'login' 		=> 'EcampCore\DbFilter\LoginFilter', 
					
					'usercamp' 		=> 'EcampCore\DbFilter\UserCampFilter',
					
					'camp' 			=> 'EcampCore\DbFilter\CampFilter',
					'period' 		=> 'EcampCore\DbFilter\PeriodFilter',
					'day' 			=> 'EcampCore\DbFilter\DayFilter',
					
					'event' 		=> 'EcampCore\DbFilter\EventFilter',
					'eventinstance' => 'EcampCore\DbFilter\EventInstanceFilter',
					
				)
			)
		)
	),
	
);