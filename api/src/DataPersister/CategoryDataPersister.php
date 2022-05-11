<?php

namespace App\DataPersister;

use App\DataPersister\Util\AbstractDataPersister;
use App\DataPersister\Util\DataPersisterObservable;
use App\Entity\BaseEntity;
use App\Entity\Category;
use App\Entity\ContentNode;
use App\Entity\ContentType;
use Doctrine\ORM\EntityManagerInterface;

class CategoryDataPersister extends AbstractDataPersister {
    public function __construct(
        DataPersisterObservable $dataPersisterObservable,
        private EntityManagerInterface $em,
    ) {
        parent::__construct(
            Category::class,
            $dataPersisterObservable,
        );
    }

    public function beforeCreate($data): BaseEntity {
        // TODO implement actual prototype cloning and strategy classes, this is just a dummy implementation to
        //      fill the non-nullable field for Doctrine
        $rootContentNode = new ContentNode();
        $rootContentNode->contentType = $this->em
            ->getRepository(ContentType::class)
            ->findOneBy(['name' => 'ColumnLayout'])
        ;
        $rootContentNode->data = [['slot' => '1', 'width' => 12]];
        $data->setRootContentNode($rootContentNode);

        return $data;
    }
}
