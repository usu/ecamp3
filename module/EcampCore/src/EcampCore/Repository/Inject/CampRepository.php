<?php

namespace EcampCore\Repository\Inject;

trait CampRepositoryTrait
{
	/**
	 * @return UserRepository
	 */
	public function getCampRepository(){
		return $this->getDependency(__TRAIT__);
	}
	
	public function setCampRepository($campRepository){
		$this->setDependency(__TRAIT__, $campRepository);
		return $this;
	}
}
