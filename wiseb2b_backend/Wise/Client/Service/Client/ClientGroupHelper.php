<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Domain\Client\ClientServiceInterface;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientGroup\Service\Interfaces\ClientGroupServiceInterface;
use Wise\Client\Service\Client\Interfaces\ClientGroupHelperInterface;
use Wise\Client\Service\Client\Interfaces\ClientHelperInterface;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractHelper;

class ClientGroupHelper extends AbstractHelper implements ClientGroupHelperInterface
{
    public function __construct(
        private readonly ClientGroupRepositoryInterface $repository,
        private readonly ClientGroupServiceInterface $clientGroupService
    ) {
        parent::__construct(
            entityDomainService: $clientGroupService
        );
    }

    public function getClientGroup(?int $id = null, ?string $idExternal = null): ?ClientGroup
    {
        $clientGroup = null;

        if (null !== $id) {
            $clientGroup = $this->repository->findOneBy(['id' => $id]);
        } elseif (null !== $idExternal) {
            $clientGroup = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $clientGroup;
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
        $id = $data['clientGroupId'] ?? null;
        $idExternal = $data['clientGroupIdExternal'] ?? $data['clientGroupExternalId'] ?? null;

        return $this->clientGroupService->getIdIfExist($id, $idExternal, $executeNotFoundException);
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
        if(!isset($data['clientGroupId']) && !isset($data['clientGroupIdExternal']) && !isset($data['clientGroupExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['clientGroupId'] ?? null;
        $idExternal = $data['clientGroupIdExternal'] ?? $data['clientGroupExternalId'] ?? null;

        $data['clientGroupId'] = $this->clientGroupService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['clientGroupIdExternal']);
        unset($data['clientGroupExternalId']);
    }
}
