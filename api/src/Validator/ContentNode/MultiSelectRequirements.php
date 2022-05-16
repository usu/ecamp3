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
                /*
                'patternProperties' => [
                    // uuid4 key
                    '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}' => [
                        '$ref' => '#/$defs/option',
                    ],
                ],*/
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
