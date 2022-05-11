<?php

namespace App\DataPersister;

use App\DataPersister\Util\AbstractDataPersister;
use App\DataPersister\Util\DataPersisterObservable;
use App\Entity\Activity;
use App\Entity\ContentNode;
use App\Util\EntityMap;

class ActivityDataPersister extends AbstractDataPersister {
    public function __construct(
        DataPersisterObservable $dataPersisterObservable
    ) {
        parent::__construct(
            Activity::class,
            $dataPersisterObservable,
        );
    }

    /**
     * @param Activity $data
     */
    public function beforeCreate($data): Activity {
        $data->camp = $data->category?->camp;

        if (!isset($data->category?->rootContentNode)) {
            throw new \UnexpectedValueException('Property rootContentNode of provided category is null. Object of type '.ContentNode::class.' expected.');
        }

        if (!is_a($data->category->rootContentNode, ContentNode::class)) {
            throw new \UnexpectedValueException('Property rootContentNode of provided category is of wrong type. Object of type '.ContentNode::class.' expected.');
        }

        $rootContentNode = new ContentNode();
        $rootContentNode->contentType = $this->em
            ->getRepository(ContentType::class)
            ->findOneBy(['name' => 'ColumnLayout'])
        ;
        $rootContentNode->data = [['slot' => '1', 'width' => 12]];
        $data->setRootContentNode($rootContentNode);

        // deep copy from category root node
        $entityMap = new EntityMap();
        $rootContentNode->copyFromPrototype($data->category->rootContentNode, $entityMap);

        return $data;
    }
}
