<?php

declare(strict_types=1);

namespace Wise\Core\Api\Attributes\OpenApi\EndpointElement;

use OpenApi\Attributes as OA;
use OpenApi\Attributes\Attachable;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\XmlContent;
use OpenApi\Generator;

/**
 * Reprezentacja pola w url dla OpenApi
 */
#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class Path extends OA\Parameter
{
    const IN = 'path';
    protected ?string $fieldEntityMapping;

    public function __construct(
        ?string $parameter = null,
        ?string $name = null,
        ?string $description = null,
        ?string $in = null,
        ?bool $required = null,
        ?bool $deprecated = null,
        ?bool $allowEmptyValue = null,
        string|object|null $ref = null,
        ?Schema $schema = null,
        mixed $example = Generator::UNDEFINED,
        ?array $examples = null,
        array|JsonContent|XmlContent|Attachable|null $content = null,
        ?string $style = null,
        ?bool $explode = null,
        ?bool $allowReserved = null,
        ?array $spaceDelimited = null,
        ?array $pipeDelimited = null,
        // annotation
        ?array $x = null,
        ?array $attachables = null,
        ?string $fieldEntityMapping = null
    ){
        $this->fieldEntityMapping = $fieldEntityMapping;

        parent::__construct(
            $parameter,
            $name,
            $description,
            self::IN,
            $required,
            $deprecated,
            $allowEmptyValue,
            $ref,
            $schema,
            $example,
            $examples,
            $content,
            $style,
            $explode,
            $allowReserved,
            $spaceDelimited,
            $pipeDelimited,
            $x,
            $attachables
        );
    }

    public function getFieldEntityMapping(): ?string
    {
        return $this->fieldEntityMapping;
    }
}

