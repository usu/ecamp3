<?php

namespace EcampCore\Repository\Dependency;

trait BaseDependency {
	
	protected $dependencyMap = array();
	
	protected function setDependency($name, $dependency){
		$this->dependencyMap[$name] = $dependency;
	}
	
	protected function getDependency($name){
		return $this->dependencyMap[$name];
	}
	
}