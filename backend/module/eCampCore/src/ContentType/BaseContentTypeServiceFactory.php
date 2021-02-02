<?php

namespace eCamp\Core\ContentType;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseContentTypeServiceFactory {
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getActivityContentId(ContainerInterface $container): ?string {
        /** @var Application $app */
        $app = $container->get('Application');

        $mvcEvent = $app->getMvcEvent();
        $routeMatch = $mvcEvent->getRouteMatch();

        return $routeMatch->getParam('activityContentId');
    }
}
