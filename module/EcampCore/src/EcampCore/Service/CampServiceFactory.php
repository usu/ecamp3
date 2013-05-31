<?php

namespace EcampCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CampServiceFactory
	implements FactoryInterface
{
	/**
	 *
	 * @param  ServiceLocatorInterface $services
	 * @return CampService
	 * @throws Exception\ServiceNotCreatedException
	 */
	public function createService(ServiceLocatorInterface $services)
	{
		$userService  = $services->get('EcampCore\Service\User');
		$em			  = $services->get('Doctrine\ORM\EntityManager');
		
		return new CampService($em, $userService);
	}
}