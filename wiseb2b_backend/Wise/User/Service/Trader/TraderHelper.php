<?php

declare(strict_types=1);

namespace Wise\User\Service\Trader;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\AbstractHelper;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Domain\Trader\TraderService;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;

class TraderHelper extends AbstractHelper implements TraderHelperInterface
{
    public function __construct(
        private readonly TraderRepositoryInterface $repository,
        private readonly TraderService $traderService
    ) {
        parent::__construct($traderService);
    }

    public function findTraderForModify(array $data): ?Trader
    {
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;
        $trader = null;

        if (null !== $id) {
            $trader = $this->repository->findOneBy(['id' => $id]);
            if (false === $trader instanceof Trader) {
                throw new ObjectNotFoundException('Nie znaleziono Trader o id: ' . $id);
            }

            return $trader;
        }

        if (null !== $idExternal) {
            $trader = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $trader;
    }

    public function getTrader(?int $id, ?string $externalId): ?Trader
    {
        $trader = null;

        if (null !== $id) {
            $trader = $this->repository->findOneBy(['id' => $id]);
        } elseif (null !== $externalId) {
            $trader = $this->repository->findOneBy(['idExternal' => $externalId]);
        }

        return $trader;
    }


    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['traderId'] ?? null;
        $idExternal = $data['traderIdExternal'] ?? $data['traderExternalId'] ?? null;

        return $this->traderService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public function prepareExternalData(array &$data, bool $executeNotFoundException = true): void
    {
        // Sprawdzam, czy istnieją pola
        if(!isset($data['traderId']) && !isset($data['traderIdExternal']) && !isset($data['traderExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['traderId'] ?? null;
        $idExternal = $data['traderIdExternal'] ?? $data['traderExternalId'] ?? null;

        $data['traderId'] = $this->traderService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['traderIdExternal']);
        unset($data['traderExternalId']);
    }
}
