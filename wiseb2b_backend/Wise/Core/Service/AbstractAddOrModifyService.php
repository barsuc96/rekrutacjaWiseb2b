<?php

namespace Wise\Core\Service;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Repository\RepositoryInterface;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

abstract class AbstractAddOrModifyService implements ApplicationServiceInterface
{
    protected const HAS_ID_EXTERNAL_FIELD = true;

    public function __construct(
        private readonly RepositoryInterface $repository,
        private readonly AbstractAddService $addService,
        private readonly AbstractModifyService $modifyService,
    ) {
    }

    public function __invoke(CommonModifyParams $params): CommonServiceDTO
    {
        $data = $params->read();

        // Weryfikacja czy istnieje encja o podanym ID lub IDExternal
        $isEntityExists = $this->checkEntityExists($data);

        if($isEntityExists){
            return ($this->modifyService)($params);
        }

        return ($this->addService)($params);
    }

    /**
     * Pobranie na podstawie danych z dto, informacji czy encja istnieje
     * @param array|null $data
     * @return bool
     */
    protected function checkEntityExists(?array $data): bool
    {
        $isExists = false;

        $id = $data['id'] ?? null;
        if ($id) {
            $isExists = $this->repository->isExists(['id' => $id]);
        }

        if(static::HAS_ID_EXTERNAL_FIELD){
            $idExternal = $data['idExternal'] ?? null;
            if ($idExternal && !$isExists) {
                $isExists = $this->repository->isExists(['idExternal' => $idExternal]);
            }
        }

        return $isExists;
    }
}
