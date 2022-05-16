<?php

namespace App\Validator\ContentNode;

use App\Validator\AssertJsonSchema;
use App\Validator\ColumnLayout\AssertColumWidthsSumTo12;
use App\Validator\ColumnLayout\AssertNoOrphanChildren;
use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class ColumnLayoutRequirements extends Compound {
    public const JSON_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['columns'],
        'properties' => [
            'columns' => [
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
            ],
        ],
    ];

    protected function getConstraints(array $options): array {
        return [
            new Assert\Sequentially(constraints: [
                new AssertJsonSchema(schema: self::JSON_SCHEMA),
                new AssertColumWidthsSumTo12(),
                new AssertNoOrphanChildren(),
            ], groups: $options['groups']),
        ];
    }
}
