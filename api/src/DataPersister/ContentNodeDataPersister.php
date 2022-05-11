<?php

namespace App\DataPersister;

use ApiPlatform\Core\Validator\ValidatorInterface as ApiValidatorInterface;
use App\DataPersister\Util\AbstractDataPersister;
use App\DataPersister\Util\DataPersisterObservable;
use App\Entity\ContentNode;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentNodeDataPersister extends AbstractDataPersister {
    /**
     * @throws \ReflectionException
     */
    public function __construct(
        DataPersisterObservable $dataPersisterObservable,
        private ValidatorInterface $validator,
        private ApiValidatorInterface $apiValidator
    ) {
        parent::__construct(
            ContentNode::class,
            $dataPersisterObservable
        );
    }

    /**
     * @param ContentNode $data
     */
    public function beforeCreate($data): ContentNode {
        // set root from parent
        $data->parent->addChild($data);
        $data->parent->root->addRootDescendant($data);

        switch ($data->getContentTypeName()) {
            case 'ColumnLayout':
                $data->data = ['columns' => [['slot' => '1', 'width' => 12]]];

                break;

            default:
        }

        return $data;
    }
}
