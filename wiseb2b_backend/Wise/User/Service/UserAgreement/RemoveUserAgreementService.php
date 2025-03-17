<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use JetBrains\PhpStorm\Pure;
use Psr\EventDispatcher\EventDispatcherInterface;
use Wise\Core\ApiAdmin\Service\DeprecatedAbstractRemoveService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ValidationException;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\User\Domain\UserAgreement\UserAgreementAfterRemoveEvent;
use Wise\User\Domain\UserAgreement\UserAgreementBeforeRemoveEvent;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\ListByFiltersUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\RemoveUserAgreementServiceInterface;

class RemoveUserAgreementService extends DeprecatedAbstractRemoveService implements RemoveUserAgreementServiceInterface
{
    #[Pure]
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RepositoryManagerInterface $repositoryManager,
        UserAgreementRepositoryInterface $userAgreementRepository,
        private readonly ListByFiltersUserAgreementServiceInterface $listByFiltersUserAgreementService
    ) {
        parent::__construct($dispatcher, $repositoryManager, $userAgreementRepository);
    }

    /**
     * @throws ValidationException
     */
    public function __invoke(CommonServiceDTO $serviceDTO, bool $continueAfterErrors = false): CommonServiceDTO
    {
        $data = $serviceDTO->read();

        // Wyciągamy id obiektów spełniających kryteria, aby je usunąć
        $userAgreementsToDelete = ($this->listByFiltersUserAgreementService)(
            $this->getFiltersForRemove($data),
            $this->getJoinsForRemove($data),
            ['id']
        );

        //Usuwamy znalezione kategorie
        $removedIds = $this->removeItems(
            $userAgreementsToDelete->read(),
            UserAgreementBeforeRemoveEvent::class,
            UserAgreementAfterRemoveEvent::class,
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
        //$joins['userAgreement1'] = new QueryJoin(UserAgreement::class, 'userAgreement1', ['userAgreementId' => 'userAgreement1.id']);

        return $joins;
    }
}
