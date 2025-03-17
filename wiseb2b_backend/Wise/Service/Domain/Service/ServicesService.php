<?php

declare(strict_types=1);


namespace Wise\Service\Domain\Service;

use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Service\Domain\Service\Exceptions\ServiceNotFoundExceptions;

class ServicesService extends AbstractEntityDomainService implements ServicesServiceInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper
    ){
        parent::__construct(
            repository:  $serviceRepository,
            notFoundException: ServiceNotFoundExceptions::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    public function findServiceForModify(array $data = []): ?Service
    {
        $service = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        if (null !== $id) {
            $service = $this->serviceRepository->findOneBy(['id' => $id]);
            if (false === $service instanceof Service) {
                throw new ObjectNotFoundException('Nie znaleziono Service o id: ' . $id);
            }

            return $service;
        }

        if (null !== $idExternal) {
            $service = $this->serviceRepository->findOneBy(['idExternal' => $idExternal]);
        }

        return $service;
    }

}
