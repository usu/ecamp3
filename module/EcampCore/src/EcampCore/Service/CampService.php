<?php

namespace EcampCore\Service;


use EcampCore\Entity\User;
use EcampCore\Entity\Group;
use EcampCore\Entity\Camp;
use EcampCore\Service\Params\Params;

class CampService
	extends ServiceBase
{
	
	/**
	 * Setup ACL
	 * @return void
	 */
	public function _setupAcl(){
		
	}
	
	public function Get()
	{
		/* @var EcampCore\Repository\CampRepository */
		$campRepo =  $this->getEM()->getRepository('EcampCore\Entity\Camp');
		$camp = $campRepo->find(1);
		
		/* @var EcampCore\Service\UserService */
		$userService = $this->getServiceLocator()->get('EcampCore\Service\User');
		$user = $userService->Get($camp->getOwner()->getId());
		
		return "camp '".$camp->getName()."' belongs to user '".$user->getUsername()."'";
	}
	
}