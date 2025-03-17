<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Wise\Core\Domain\Interfaces\EntityDomainServiceInterface;
use Wise\Core\Service\Interfaces\CommonHelperInterface;

abstract class AbstractHelper implements CommonHelperInterface
{
    public function __construct(
        private readonly EntityDomainServiceInterface $entityDomainService
    ){}

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param int|null $id
     * @param string|null $idExternal
     * @param bool $executeNotFoundException
     * @return int|null
     * @throws \ReflectionException
     */
    public function getIdIfExist(?int $id = null, ?string $idExternal = null, bool $executeNotFoundException = true): ?int
    {
        return $this->entityDomainService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public function getIdIfExistByData(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        return $this->entityDomainService->getIdIfExist($id, $idExternal, $executeNotFoundException);
    }

    /**
     * Zwraca id z zewnętrzne z systemu klienta
     * @param int $id
     * @param bool $executeNotFoundException
     * @return string|null
     */
    public function getIdExternal(int $id, bool $executeNotFoundException = true): ?string
    {
        if($this->entityDomainService->hasPropertyIdExternal()){
            $entity = $this->entityDomainService->findEntityForModify(
                id: $id,
                executeNotFoundException: $executeNotFoundException
            );

            return $entity?->getIdExternal();
        }

        return null;
    }

    /**
     * Zwraca identyfikator encji na podstawie date, jeśli znajdują się tam zewnętrzne klucze
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public abstract function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int;

    /**
     * Metoda pobiera dane związane z perzystnecją encji i przygotowuje je do zapisu
     * Dla przykładu jeśli w danych zewnętrznych znajduje się klucz productIdExternal, to metoda zamienia go na productId i sprawdza czy taki produkt istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return void
     */
    public abstract function prepareExternalData(array &$data, bool $executeNotFoundException = true): void;
}
