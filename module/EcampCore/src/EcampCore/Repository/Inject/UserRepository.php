<?php

namespace EcampCore\Repository\Dependency;

trait UserRepository
{
//	use BaseDependency;
	
	/**
	 * @return UserRepository
	 */
	public function getUserRepository(){
		return $this->getDependency(__TRAIT__);
	}
	
	public function setUserRepository($userRepository){
		$this->setDependency(__TRAIT__, $userRepository);
		return $this;
	}
	
}
