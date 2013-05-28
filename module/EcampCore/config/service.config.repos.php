<?php
return array(

	'factories' => array(
		'ecampcore.repo.camp' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Camp'),
		'ecampcore.repo.camptype' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\CampType'),
		'ecampcore.repo.day' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Day'),
		'ecampcore.repo.event' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Event'),
		'ecampcore.repo.eventcategory' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventCategory'),
		'ecampcore.repo.eventinstance' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventInstance'),
		'ecampcore.repo.eventprototype' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventPrototype'),
		'ecampcore.repo.eventresp' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventResp'),
		'ecampcore.repo.eventtemplate' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventTemplate'),
		'ecampcore.repo.eventtype' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\EventType'),
		'ecampcore.repo.group' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Group'),
		'ecampcore.repo.grouprequest' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\GroupRequest'),
		'ecampcore.repo.image' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Image'),
		'ecampcore.repo.login' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Login'),
		'ecampcore.repo.medium' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Medium'),
		'ecampcore.repo.period' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Period'),
		'ecampcore.repo.plugin' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\Plugin'),
		'ecampcore.repo.plugininstance' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\PluginInstance'),
		'ecampcore.repo.pluginposition' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\PluginPosition'),
		'ecampcore.repo.pluginprototype' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\PluginPrototype'),
		'ecampcore.repo.uid' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\UId'),
		'ecampcore.repo.user' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\User'),
		'ecampcore.repo.usercamp' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\UserCamp'),
		'ecampcore.repo.usergroup' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\UserGroup'),
		'ecampcore.repo.userrelationship' => new EcampCore\RepositoryUtil\RepositoryFactory('EcampCore\Entity\UserRelationship'),
	),
	
	'initializers' => array(
		function($instance, $sm){
			
			if(! is_object($instance)){
				return;
			}
			
			foreach(class_uses($instance) as $trait){
				switch($trait){
					
					case 'EcampCore\RepositoryTraits\CampTrait':
						$instance->setCampRepository($sm->get('ecampcore.repo.camp'));
						break;

					case 'EcampCore\RepositoryTraits\CampTypeTrait':
						$instance->setCampTypeRepository($sm->get('ecampcore.repo.camptype'));
						break;

					case 'EcampCore\RepositoryTraits\DayTrait':
						$instance->setDayRepository($sm->get('ecampcore.repo.day'));
						break;

					case 'EcampCore\RepositoryTraits\EventTrait':
						$instance->setEventRepository($sm->get('ecampcore.repo.event'));
						break;

					case 'EcampCore\RepositoryTraits\EventCategoryTrait':
						$instance->setEventCategoryRepository($sm->get('ecampcore.repo.eventcategory'));
						break;

					case 'EcampCore\RepositoryTraits\EventInstanceTrait':
						$instance->setEventInstanceRepository($sm->get('ecampcore.repo.eventinstance'));
						break;

					case 'EcampCore\RepositoryTraits\EventPrototypeTrait':
						$instance->setEventPrototypeRepository($sm->get('ecampcore.repo.eventprototype'));
						break;

					case 'EcampCore\RepositoryTraits\EventRespTrait':
						$instance->setEventRespRepository($sm->get('ecampcore.repo.eventresp'));
						break;

					case 'EcampCore\RepositoryTraits\EventTemplateTrait':
						$instance->setEventTemplateRepository($sm->get('ecampcore.repo.eventtemplate'));
						break;

					case 'EcampCore\RepositoryTraits\EventTypeTrait':
						$instance->setEventTypeRepository($sm->get('ecampcore.repo.eventtype'));
						break;

					case 'EcampCore\RepositoryTraits\GroupTrait':
						$instance->setGroupRepository($sm->get('ecampcore.repo.group'));
						break;

					case 'EcampCore\RepositoryTraits\GroupRequestTrait':
						$instance->setGroupRequestRepository($sm->get('ecampcore.repo.grouprequest'));
						break;

					case 'EcampCore\RepositoryTraits\ImageTrait':
						$instance->setImageRepository($sm->get('ecampcore.repo.image'));
						break;

					case 'EcampCore\RepositoryTraits\LoginTrait':
						$instance->setLoginRepository($sm->get('ecampcore.repo.login'));
						break;

					case 'EcampCore\RepositoryTraits\MediumTrait':
						$instance->setMediumRepository($sm->get('ecampcore.repo.medium'));
						break;

					case 'EcampCore\RepositoryTraits\PeriodTrait':
						$instance->setPeriodRepository($sm->get('ecampcore.repo.period'));
						break;

					case 'EcampCore\RepositoryTraits\PluginTrait':
						$instance->setPluginRepository($sm->get('ecampcore.repo.plugin'));
						break;

					case 'EcampCore\RepositoryTraits\PluginInstanceTrait':
						$instance->setPluginInstanceRepository($sm->get('ecampcore.repo.plugininstance'));
						break;

					case 'EcampCore\RepositoryTraits\PluginPositionTrait':
						$instance->setPluginPositionRepository($sm->get('ecampcore.repo.pluginposition'));
						break;

					case 'EcampCore\RepositoryTraits\PluginPrototypeTrait':
						$instance->setPluginPrototypeRepository($sm->get('ecampcore.repo.pluginprototype'));
						break;

					case 'EcampCore\RepositoryTraits\UIdTrait':
						$instance->setUIdRepository($sm->get('ecampcore.repo.uid'));
						break;

					case 'EcampCore\RepositoryTraits\UserTrait':
						$instance->setUserRepository($sm->get('ecampcore.repo.user'));
						break;

					case 'EcampCore\RepositoryTraits\UserCampTrait':
						$instance->setUserCampRepository($sm->get('ecampcore.repo.usercamp'));
						break;

					case 'EcampCore\RepositoryTraits\UserGroupTrait':
						$instance->setUserGroupRepository($sm->get('ecampcore.repo.usergroup'));
						break;

					case 'EcampCore\RepositoryTraits\UserRelationshipTrait':
						$instance->setUserRelationshipRepository($sm->get('ecampcore.repo.userrelationship'));
						break;

					default:
						break;
				}
			}
		}
	)
);