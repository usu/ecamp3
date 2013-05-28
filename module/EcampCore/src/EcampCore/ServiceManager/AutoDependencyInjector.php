<?php

namespace EcampCore\ServiceManager;

use Zend\Code\Reflection\MethodReflection;

use Zend\Code\Reflection\ClassReflection;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class AutoDependencyInjector
	implements ServiceLocatorAwareInterface
{
	
	/**
	 * @var Zend\ServiceManager\ServiceManager
	 */
	private $serviceLocator;
	
	public function getServiceLocator(){
		return $this->serviceLocator;
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator){
		$this->serviceLocator = $serviceLocator;
	}
	
	
	public function InjectDependencies($instance){
		$this->scanForDependencies($instance);
	}
	
	
	private function scanForDependencies($instance){
		
		//$dependencies = array();
		
		$class = new ClassReflection($instance);
		$methods = $class->getMethods(MethodReflection::IS_PUBLIC);
		
		foreach($methods as $method){	
			/* @var $method \Zend\Server\Reflection\MethodReflection */
			$docBlock = $method->getDocBlock();
			
			if($docBlock && $docBlock->hasTag('Inject')){
				$injectTag = $docBlock->getTag('Inject');
				
				echo $injectTag->getContent();
			}
		}
		
	}
	
}