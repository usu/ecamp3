<?php

namespace EcampCore\Service;

use EcampCore\Acl\DefaultAcl;
use EcampCore\Entity\User;
use EcampCore\Service\Params\Params;

class UserService 
	extends ServiceBase
{
	
	/**
	 * Setup ACL
	 * @return void
	 */
	public function _setupAcl(){
	}
	
	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}
	
	
	public function Get($id)
	{
		$user = $this->getEM()->find('EcampCore\Entity\User',$id);
		return $user;
	}
	
}