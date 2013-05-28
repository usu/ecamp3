<?php

namespace EcampCore\Acl;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use EcampLib\Acl\Acl;

class AclFactory
	implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $serviceLocator){
		
		$acl = new Acl();
		
		$config = $serviceLocator->get('config');
		
		$roles = $config->ecamp->roles->toArray();
		$resources = $config->ecamp->resources->toArray();
		
		var_dump($roles);
		var_dump($resources);

		$acl->allow(User::ROLE_USER, Resource::User, 'list', new Assertion\AssertUserList());
		$acl->allow(User::ROLE_USER, Resource::User, 'show', new Assertion\AssertUserShow());
		$acl->allow(User::ROLE_USER, Resource::User, 'visit', new Assertion\AssertUserVisit());
		$acl->allow(User::ROLE_USER, Resource::User, 'administrate', new Assertion\AssertUserAdministrate());
		 
		$acl->allow(User::ROLE_USER, Resource::CAMP, 'list', new Assertion\AssertCampList());
		$acl->allow(User::ROLE_USER, Resource::CAMP, 'visit', new Assertion\AssertCampVisit());
		$acl->allow(User::ROLE_USER, Resource::CAMP, 'contribute', new Assertion\AssertCampContribute());
		$acl->allow(User::ROLE_USER, Resource::CAMP, 'configure', new Assertion\AssertCampConfigure());
		$acl->allow(User::ROLE_USER, Resource::CAMP, 'administrate', new Assertion\AssertCampAdministrate());
		 
		$acl->allow(User::ROLE_USER, Resource::GROUP, 'list', new Assertion\AssertGroupList());
		$acl->allow(User::ROLE_USER, Resource::GROUP, 'visit', new Assertion\AssertGroupVisit());
		$acl->allow(User::ROLE_USER, Resource::GROUP, 'contribute', new Assertion\AssertGroupContribute());
		$acl->allow(User::ROLE_USER, Resource::GROUP, 'administrate', new Assertion\AssertGroupAdministrate());
		
	}
}