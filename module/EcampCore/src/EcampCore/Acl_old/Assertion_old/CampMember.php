<?php

namespace EcampCore\Acl\Assertion;

use EcampCore\Entity\Camp;

use EcampCore\Acl\Role;
use EcampCore\Acl\Resource;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Assertion\AssertionInterface;

class CampMember extends AssertionInterface
{
	
	public function assert(
		Acl $acl, 
		RoleInterface $role = null, 
		ResourceInterface $resource = null, 
		$privilege = null
	){
		if($role instanceof Role){
			$user = $role->getUser();
		} else {
			// Only a User can be a CampOwner
			return false;
		}

		if($resource instanceof Resource && $resource->getEntity() instanceof Camp){
			$camp = $resource->getEntity();
		} else {
			// Only a Camp can have a CampOwner
			return false;
		}
		
		if($camp->getOwner()->getId() == $user->getId()){
			return true;
		}
		
		$criteria = Criteria::create();
		$expr = Criteria::expr();
		$criteria->setMaxResults(1);
		$criteria->where($expr->eq('user', $user));
		$criteria->andWhere($expr->orX(
			$expr->eq('role', UserCamp::ROLE_MEMBER),
			$expr->eq('role', UserCamp::ROLE_MANAGER)
		));
		
		return !$camp->getUserCamps()->matching($criteria)->isEmpty();
	}
}
