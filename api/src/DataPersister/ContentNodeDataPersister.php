<?php

namespace App\DataPersister;

use ApiPlatform\Core\Validator\ValidatorInterface as ApiValidatorInterface;
use App\DataPersister\Util\AbstractDataPersister;
use App\DataPersister\Util\DataPersisterObservable;
use App\Entity\ContentNode;
use App\InputFilter\CleanHTMLFilter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentNodeDataPersister extends AbstractDataPersister {
    /**
     * @throws \ReflectionException
     */
    public function __construct(
        DataPersisterObservable $dataPersisterObservable,
        private ValidatorInterface $validator,
        private ApiValidatorInterface $apiValidator,
        private CleanHTMLFilter $cleanHTMLFilter
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

            case 'Notes':
            case 'SafetyConcept':
            case 'Storycontext':
                $data->data = ['text' => ''];

                break;

            default:
        }

        return $data;
    }

    /**
     * @param ContentNode $data
     */
    public function beforeUpdate($data): ContentNode {
        switch ($data->getContentTypeName()) {
            case 'ColumnLayout':
                break;

            case 'Notes':
            case 'SafetyConcept':
            case 'Storycontext':
                $data->data = $this->cleanHTMLFilter->applyTo($data->data, 'text');

                break;

            default:
        }

        return $data;
    }
}
