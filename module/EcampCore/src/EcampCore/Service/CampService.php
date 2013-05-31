<?php

namespace EcampCore\Service;


use EcampCore\Entity\User;
use EcampCore\Entity\Group;
use EcampCore\Entity\Camp;
use EcampCore\Service\Params\Params;

use EcampCore\Repository\CampRepository;
use EcampCore\Service\UserService;

class CampService
	extends ServiceBase
{
	
	/**
	 * Setup ACL
	 * @return void
	 */
	public function _setupAcl(){
		
	}
	
	/* @var EcampCore\Repository\CampRepository */
	protected $campRepo = null;
	
	/* @var EcampCore\Service\UserService */
	protected $userService = null;
	
	public function __construct(\Doctrine\ORM\EntityManager $em, UserService $userService)
	{
		$this->userService = $userService;
		$this->em = $em;
		
		$this->campRepo = $campRepo = $this->getEM()->getRepository('EcampCore\Entity\Camp');
	}
	
	public function Get()
	{
		
		$camp = $this->campRepo->find(1);
		$user = $this->userService->Get($camp->getOwner()->getId());
		
		return "camp '".$camp->getName()."' belongs to user '".$user->getUsername()."'";
	}
	
}