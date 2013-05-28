<?php

namespace EcampLib\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;

use EcampCore\Entity\User;
use EcampCore\Entity\BaseEntity;
use EcampLib\Acl\Exception\NoAccessException;
use EcampLib\Acl\Exception\AuthentificationRequiredException;

class Acl 
	extends ZendAcl
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