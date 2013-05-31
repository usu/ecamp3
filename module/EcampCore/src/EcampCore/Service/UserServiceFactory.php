<?php

namespace EcampCore\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory
	implements FactoryInterface
{
	/**
	 *
	 * @param  ServiceLocatorInterface $services
	 * @return UserService
	 * @throws Exception\ServiceNotCreatedException
	 */
	public function createService(ServiceLocatorInterface $services)
	{
		$em		= $services->get('Doctrine\ORM\EntityManager');
		
		return new UserService($em);
	}
}