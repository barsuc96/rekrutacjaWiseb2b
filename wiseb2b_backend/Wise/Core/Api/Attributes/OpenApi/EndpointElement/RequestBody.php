<?php

declare(strict_types=1);

namespace Wise\Core\Api\Attributes\OpenApi\EndpointElement;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\AdditionalProperties;
use OpenApi\Attributes\Discriminator;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Xml;
use OpenApi\Generator;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class RequestBody extends OA\Property
{
    public function __construct(
        ?string $property = null,
        string|object|null $ref = null,
        ?string $schema = null,
        ?string $title = null,
        ?string $description = null,
        ?int $maxProperties = null,
        ?int $minProperties = null,
        ?array $required = null,
        ?array $properties = null,
        ?string $type = null,
        ?string $format = null,
        ?Items $items = null,
        ?string $collectionFormat = null,
        mixed $default = Generator::UNDEFINED,
        $maximum = null,
        ?bool $exclusiveMaximum = null,
        $minimum = null,
        ?bool $exclusiveMinimum = null,
        ?int $maxLength = null,
        ?int $minLength = null,
        ?int $maxItems = null,
        ?int $minItems = null,
        ?bool $uniqueItems = null,
        ?string $pattern = null,
        array|string|null $enum = null,
        ?Discriminator $discriminator = null,
        ?bool $readOnly = null,
        ?bool $writeOnly = null,
        ?Xml $xml = null,
        ?ExternalDocumentation $externalDocs = null,
        mixed $example = Generator::UNDEFINED,
        ?bool $nullable = null,
        ?bool $deprecated = null,
        ?array $allOf = null,
        ?array $anyOf = null,
        ?array $oneOf = null,
        AdditionalProperties|bool|null $additionalProperties = null,
        ?array $x = null,
        ?array $attachables = null
    ){
        parent::__construct(
            $property,
            $ref,
            $schema,
            $title,
            $description,
            $maxProperties,
            $minProperties,
            $required,
            $properties,
            $type,
            $format,
            $items,
            $collectionFormat,
            $default,
            $maximum,
            $exclusiveMaximum,
            $minimum,
            $exclusiveMinimum,
            $maxLength,
            $minLength,
            $maxItems,
            $minItems,
            $uniqueItems,
            $pattern,
            $enum,
            $discriminator,
            $readOnly,
            $writeOnly,
            $xml,
            $externalDocs,
            $example,
            $nullable,
            $deprecated,
            $allOf,
            $anyOf,
            $oneOf,
            $additionalProperties,
            $x,
            $attachables
        );
    }
}
