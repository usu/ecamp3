<?php

namespace EcampCore\Acl;


use EcampCore\Acl\Exception\AuthentificationRequiredException;

use EcampCore\Entity\User;
use EcampCore\Entity\BaseEntity;

use Zend\Permissions\Acl\Acl;
use EcampCore\Acl\Exception\NoAccessException;

class DefaultAcl 
	extends Acl
{
	
    /**
     * Setup roles
     */
    public function __construct()
    {
    	/* Roles */
    	$this->addRole(User::ROLE_GUEST);
    	$this->addRole(User::ROLE_USER, User::ROLE_GUEST);
    	$this->addRole(User::ROLE_ADMIN, User::ROLE_USER);
    	
    	/* Base-Resources */
    	$this->addResource(Resource::USER);
    	$this->addResource(Resource::GROUP);
    	$this->addResource(Resource::CAMP);
    	
    	$this->addResource('EcampCore\Entity\Period', Resource::CAMP);
    	
    	
    	$this->getAcl()->aloow(User::ROLE_ADMIN, Resouce::User);
    	$this->getAcl()->aloow(User::ROLE_ADMIN, Resouce::Camp);
    	$this->getAcl()->aloow(User::ROLE_ADMIN, Resouce::Group);


    	$this->allow(User::ROLE_USER, Resource::User, 'list', new AssertUserList());
    	$this->allow(User::ROLE_USER, Resource::User, 'show', new AssertUserShow());
    	$this->allow(User::ROLE_USER, Resource::User, 'visit', new AssertUserVisit());
    	$this->allow(User::ROLE_USER, Resource::User, 'administrate', new AssertUserAdministrate());
    	
    	$this->allow(User::ROLE_USER, Resource::CAMP, 'list', new AssertCampList());
    	$this->allow(User::ROLE_USER, Resource::CAMP, 'visit', new AssertCampVisit());
    	$this->allow(User::ROLE_USER, Resource::CAMP, 'contribute', new AssertCampContribute());
    	$this->allow(User::ROLE_USER, Resource::CAMP, 'configure', new AssertCampConfigure());
    	$this->allow(User::ROLE_USER, Resource::CAMP, 'administrate', new AssertCampAdministrate());
    	
    	$this->allow(User::ROLE_USER, Resource::GROUP, 'list', new AssertGroupList());
    	$this->allow(User::ROLE_USER, Resource::GROUP, 'visit', new AssertGroupVisit());
    	$this->allow(User::ROLE_USER, Resource::GROUP, 'contribute', new AssertGroupContribute());
    	$this->allow(User::ROLE_USER, Resource::GROUP, 'administrate', new AssertGroupAdministrate());
    	
    }
	
    public function isAllowed(User $user = null, BaseEntity $entity = null, $privilege = null){
		
    	$role = User::ROLE_GUEST;		
		if($user != null){
			$role = new Role($user);
			$role->register($this);
		}
		
		$resource = ($entity != null) ? Resource::Create($entity) : null;
		if($resource != null){
			$resource->register($this);
		}
		
		return parent::isAllowed($role, $entity, $privilege);
	}
	
	public function isAllowedException(User $user = null, BaseEntity $entity = null, $privilege = null){
		if($this->isAllowed($user, $entity, $privilege)){
			return true;
		} else {
			if($user == null){
				throw new AuthentificationRequiredException();
			} else {
				throw new NoAccessException("No Access");
			}
		}
	}
}