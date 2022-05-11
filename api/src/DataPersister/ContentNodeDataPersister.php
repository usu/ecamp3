<?php

namespace App\DataPersister;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\DataPersister\Util\AbstractDataPersister;
use App\DataPersister\Util\DataPersisterObservable;
use App\Entity\ContentNode;
use App\Validator\AssertJsonSchema;
use App\Validator\ColumnLayout\AssertColumWidthsSumTo12;
use App\Validator\ColumnLayout\AssertNoOrphanChildren;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContentNodeDataPersister extends AbstractDataPersister {
    public const COLUMNS_SCHEMA = [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'additionalProperties' => false,
            'required' => ['slot', 'width'],
            'properties' => [
                'slot' => [
                    'type' => 'string',
                    'pattern' => '^[1-9][0-9]*$',
                ],
                'width' => [
                    'type' => 'integer',
                    'minimum' => 3,
                    'maximum' => 12,
                ],
            ],
        ],
    ];

    /**
     * @throws \ReflectionException
     */
    public function __construct(
        DataPersisterObservable $dataPersisterObservable,
        private ValidatorInterface $validator
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

        $this->validateDataProperty($data);

        return $data;
    }

    /**
     * @param ContentNode $data
     */
    public function beforeUpdate($data): ContentNode {
        $this->validateDataProperty($data);

        return $data;
    }

    /**
     * @param ContentNode $data
     */
    private function validateDataProperty($data) {
        switch ($data->getContentTypeName()) {
            case 'ColumnLayout':
                $this->validate($data->data, new AssertJsonSchema(schema: self::COLUMNS_SCHEMA));
                $this->validate($data->data, new AssertColumWidthsSumTo12());
                // $this->validate($data->data, new AssertNoOrphanChildren());

                break;
        }
    }

    private function validate(mixed $value, Constraint|array $constraints) {
        $violations = $this->validator->validate($value, $constraints);

        if (0 !== \count($violations)) {
            throw new ValidationException($violations);
        }
    }
}
