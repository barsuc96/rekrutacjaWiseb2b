<?php

declare(strict_types=1);

namespace Wise\Core\Dto\Attribute;

use Attribute;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\ExternalDocumentation;
use OpenApi\Attributes\RequestBody;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Exception\DtoParseException;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class CommonDeleteDtoParamAttributes extends OA\Delete
{
    public function __construct(
        ?string $path = null,
        ?string $operationId = null,
        ?string $description = null,
        ?string $summary = null,
        ?array $security = null,
        ?array $servers = null,
        ?RequestBody $requestBody = null,
        ?array $tags = null,
        ?array $parameters = null,
        ?array $responses = null,
        ?array $callbacks = null,
        ?ExternalDocumentation $externalDocs = null,
        ?bool $deprecated = null,
        ?array $x = null,
        ?array $attachables = null,
        ?string $parametersDtoClass = null,
    ) {
        $dto = new ($parametersDtoClass)();

        if (!($dto instanceof AbstractDto)) {
            throw new DtoParseException(
                'Cannot automatically create parameters because ' . $parametersDtoClass . ' is not an instance of ' . AbstractDto::class
            );
        }

        parent::__construct(
            $path,
            $operationId,
            $description,
            $summary,
            $security,
            $servers,
            $requestBody,
            $tags,
            $dto->listParameters(),
            $responses,
            $callbacks,
            $externalDocs,
            $deprecated,
            $x,
            $attachables
        );
    }
}
