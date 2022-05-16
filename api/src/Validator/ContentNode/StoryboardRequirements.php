<?php

namespace App\Validator\ContentNode;

use App\Validator\AssertJsonSchema;
use Attribute;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class StoryboardRequirements extends Compound {
    public const JSON_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['sections'],
        'properties' => [
            'sections' => [
                'type' => 'object',
                'patternProperties' => [
                    // uuid4 key
                    '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}' => [
                        '$ref' => '#/$defs/section',
                    ],
                ],
                'additionalProperties' => false,
            ],
        ],
        '$defs' => [
            'section' => [
                'type' => 'object',
                'additionalProperties' => false,
                'required' => ['column1', 'column2', 'column3', 'position'],
                'properties' => [
                    'column1' => [
                        'type' => 'string',
                    ],
                    'column2' => [
                        'type' => 'string',
                    ],
                    'column3' => [
                        'type' => 'string',
                    ],
                    'position' => [
                        'type' => 'integer',
                        'minimum' => 1,
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
