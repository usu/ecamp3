<?php

namespace App\Validator\ContentNode;

use App\Validator\AssertJsonSchema;
use Attribute;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class SingleTextRequirements extends Compound {
    public const JSON_SCHEMA = [
        'type' => 'object',
        'additionalProperties' => false,
        'required' => ['text'],
        'properties' => [
            'text' => [
                'type' => 'string',
            ],
        ],
    ];

    protected function getConstraints(array $options): array {
        return [
            new AssertJsonSchema(schema: self::JSON_SCHEMA),
        ];
    }
}
