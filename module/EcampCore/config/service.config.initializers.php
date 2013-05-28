<?php

return array(
	'initializers' => array(
		function($instance, $sm){
			
			foreach(class_uses($instance) as $trait){
				switch($trait){
					
					case 'EcampCore\Repository\Dependency\UserRepository':
						$instance->setUserRepository($sm->get('ecampcore.repo.user'));
						break;
					
					case 'EcampCore\Repository\Dependency\CampRepository':
						$instance->setCampRepository($sm->get('ecampcore.repo.camp'));
					
					default:
						break;
				}
			}
		}
	)	
);