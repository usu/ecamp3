<?php

namespace App\Validator\ContentNode;

use App\Validator\AssertJsonSchema;
use Attribute;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class MultiSelectRequirements extends Compound {
    public const JSON_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['options'],
        'properties' => [
            'options' => [
                'type' => 'object',
                'additionalProperties' => ['$ref' => '#/$defs/option'],
            ],
        ],
        '$defs' => [
            'option' => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => ['checked'],
                'properties' => [
                    'checked' => [
                        'type' => 'boolean',
                    ],
                ],
            ],
        ],
    ];

    protected function getConstraints(array $options): array {
        return [
            new AssertJsonSchema(schema: self::JSON_SCHEMA),
        ];
    }
}
