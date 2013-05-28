<?php
return array(
	
	'aliases' => array(
		'EcampCore\Acl\ContextStorage' => 'ecamp.acl.contextstorage'
	),
	
	'factories' => array(
		'Router'        => 'EcampCore\Router\RouterFactory',
			
		'ecampcore.internal.acl' => function($sm){	return new EcampCore\Acl\DefaultAcl($sm);	},
		
		'ecampcore.acl.contextprovider'	=> function($sm){
			$contextStorage = $sm->get('ecampcore.acl.contextstorage');
			return new EcampCore\Acl\ContextProvider($contextStorage);
		},
		
		'ecampcore.acl.contextstorage' => function($sm){
			$authService = new Zend\Authentication\AuthenticationService();
			$userRepo = $sm->get('ecampcore.repo.user');
			$groupRepo = $sm->get('ecampcore.repo.group');
			$campRepo = $sm->get('ecampcore.repo.camp');
			return new EcampCore\Acl\ContextStorage($authService, $userRepo, $groupRepo, $campRepo);
		},
		
	),
	
	'invokables' => array(
		'EcampCore\ServiceManager\AutoDependencyInjector' => 'EcampCore\ServiceManager\AutoDependencyInjector'
	),
	
	'initializers' => array(
		function($instance, $sm){
			
// 			$traits = class_uses($instance);
			
// 			foreach($traits as $trait){
// 				if(property_exists($trait, 'dependency_name')){
// 					$dependency = $trait::dependency_name;
// 					$setter = $trait::setter_method;
					
// 					$instance->$setter($sm->get($dependency));
// 				}
// 			}
			
// 			if($instance instanceof EcampCore\ServiceManager\AutoInjectDependenciesInterface){
// 				$dependencyInjector = $sm->get('EcampCore\ServiceManager\AutoDependencyInjector');
				
// 				echo get_class($instance) . PHP_EOL;
// 				$dependencyInjector->InjectDependencies($instance);
				
// 			}
		},
	),
);