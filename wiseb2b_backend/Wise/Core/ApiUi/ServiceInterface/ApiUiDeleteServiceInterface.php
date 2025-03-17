<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\ServiceInterface;

use Symfony\Component\HttpFoundation\JsonResponse;
use Wise\Core\Dto\AbstractDto;

/** @template T of AbstractDto */
interface ApiUiDeleteServiceInterface
{
    /** @param T $dto */
    public function delete(AbstractDto $dto): void;

    /**
     * @param array $attributes Lista parametrów z żądania, którymi zostanie zasilony DTO wskazany w $dtoClass
     * @param class-string<T> $dtoClass FQCN do DTO, który ma zostać utworzony
     * @param array<string, mixed> $additionalParameters Dodatkowe parametry wstrzyknięte do utworzonego DTO
     *
     * @return JsonResponse Odpowiedź wysłana do użytkownika
     */
    public function process(array $attributes, string $dtoClass, array $additionalParameters = []): JsonResponse;
}
