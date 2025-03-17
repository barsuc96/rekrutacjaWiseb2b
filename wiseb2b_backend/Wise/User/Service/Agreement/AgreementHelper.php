<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\AgreementHelperInterface;

class AgreementHelper implements AgreementHelperInterface
{
    public function __construct(private readonly AgreementRepositoryInterface $repository) {}

    public function findAgreementForModify(array $data): ?Agreement
    {
        $agreement = null;
        $id = $data['id'] ?? null;
        $symbol = $data['symbol'] ?? null;

        if (null !== $id) {
            $agreement = $this->repository->findOneBy(['id' => $id]);
            if (false === $agreement instanceof Agreement) {
                throw new ObjectNotFoundException('Nie znaleziono Agreement o id: ' . $id);
            }

            return $agreement;
        }

        if (null !== $symbol) {
            $agreement = $this->repository->findOneBy(['symbol' => $symbol]);
        }

        return $agreement;
    }

    public function getAgreement(?int $id, ?string $symbol): Agreement
    {
        $agreement = null;

        if (null !== $id) {
            $agreement = $this->repository->findOneBy(['id' => $id]);
        } elseif (null !== $symbol) {
            $agreement = $this->repository->findOneBy(['symbol' => $symbol]);
        }

        if (false === $agreement instanceof Agreement) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt Agreement nie istnieje. Id: %s, symbol: %s', $id, $symbol)
            );
        }

        return $agreement;
    }

    public function findOrCreateAgreement()
    {

    }
}
