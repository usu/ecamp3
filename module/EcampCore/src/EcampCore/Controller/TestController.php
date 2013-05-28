<?php

namespace EcampCore\Controller;

use EcampCore\Repository\UserRepository;
use EcampCore\Repository\CampRepository;

use Zend\View\Model\ViewModel;

class TestController extends AbstractBaseController
{
	
	public function indexAction(){
		
		$this->getCampRepository()->
		
// 		$di = $this->getServiceLocator()->get('DI');
// 		$di->get('EcampCore\Controller\TestController');
		
// 		$cr = new \Zend\Config\Writer\PhpArray();
// 		echo $cr->processConfig($this->getServiceLocator()->get('Config'));
		
		$user = $this->userRepo->find('2de20f49');
		$users = array($user);
		$camps = array($this->ecampCore_CampRepo()->find('cc1'));
		
		foreach($camps as $camp){
			echo "Camp " . $camp->getId() . ":" . PHP_EOL;
			
			foreach($users as $user){
				echo "    User " . $user->getId() . ": ";
				echo $camp->isMember($user) ? "1" : "0";
				echo PHP_EOL;
			}
			echo PHP_EOL;
		}
		
		$viewModel = new ViewModel();
		$viewModel->setTemplate("ecamp-core/index/index");
		return $viewModel;
	}
	
}