<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\User\Domain\Agreement\AgreementAfterRemoveEvent;
use Wise\User\Domain\Agreement\AgreementBeforeRemoveEvent;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\ListByFiltersAgreementServiceInterface;
use Wise\User\Service\Agreement\Interfaces\RemoveAgreementServiceInterface;

class RemoveAgreementService extends DeprecatedAbstractRemoveService implements RemoveAgreementServiceInterface
{
    #[Pure]
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RepositoryManagerInterface $repositoryManager,
        AgreementRepositoryInterface $agreementRepository,
        private readonly ListByFiltersAgreementServiceInterface $listByFiltersAgreementService
    ) {
        parent::__construct($dispatcher, $repositoryManager, $agreementRepository);
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CommonServiceDTO $serviceDTO, bool $continueAfterErrors = false): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        // Wyciągamy id obiektów spełniających kryteria, aby je usunąć
        $agreementsToDelete = ($this->listByFiltersAgreementService)(
            $this->getFiltersForRemove($data),
            $this->getJoinsForRemove($data),
            ['id']
        );

        //Usuwamy znalezione kategorie
        $removedIds = $this->removeItems(
            $agreementsToDelete->read(),
            AgreementBeforeRemoveEvent::class,
            AgreementAfterRemoveEvent::class,
            $continueAfterErrors
        );

        $result = (new CommonServiceDTO());
        $result->writeAssociativeArray($removedIds);

        return $result;
    }

    #[Pure]
    protected function getJoinsForRemove(array $data): array
    {
        $joins = [];

        //Jeśli obiekt który usuwamy są powiązane to tutaj dodajemy to powiązanie np.
        //$joins['agreement1'] = new QueryJoin(Agreement::class, 'agreement1', ['agreementId' => 'agreement1.id']);

        return $joins;
    }
}
