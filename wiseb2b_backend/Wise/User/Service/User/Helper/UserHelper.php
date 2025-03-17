<?php

declare(strict_types=1);

namespace Wise\User\Service\User\Helper;

use Wise\Core\Service\AbstractHelper;
use Wise\User\Domain\User\UserServiceInterface;
use Wise\User\Service\User\Helper\Interfaces\UserHelperInterface;

class UserHelper extends AbstractHelper implements UserHelperInterface
{

    public function __construct(
        private readonly UserServiceInterface $userService,
    ){
        parent::__construct(
            entityDomainService: $userService
        );
    }

    /**
     * Zwraca identyfikator encji, jeśli istnieje
     * @param array $data
     * @param bool $executeNotFoundException
     * @return int|null
     */
    public function getIdIfExistByDataExternal(array $data, bool $executeNotFoundException = true): ?int
    {
        $id = $data['userId'] ?? null;
        $idExternal = $data['userIdExternal'] ?? $data['userExternalId'] ?? null;

        return $this->userService->getIdIfExist($id, $idExternal, $executeNotFoundException);
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
        if(!isset($data['userId']) && !isset($data['userIdExternal']) && !isset($data['userExternalId'])){
            return;
        }

        // Pobieram identyfikator
        $id = $data['userId'] ?? null;
        $idExternal = $data['userIdExternal'] ?? $data['userExternalId'] ?? null;

        $data['userId'] = $this->userService->getIdIfExist($id, $idExternal, $executeNotFoundException);

        // Usuwam pola zewnętrzne
        unset($data['userIdExternal']);
        unset($data['userExternalId']);
    }
}
