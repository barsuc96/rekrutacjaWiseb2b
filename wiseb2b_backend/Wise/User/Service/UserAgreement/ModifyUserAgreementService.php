<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\AgreementHelperInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;
use Wise\User\Service\UserAgreement\Interfaces\ModifyUserAgreementServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementHelperInterface;
use Wise\Core\Service\Merge\MergeService;

class ModifyUserAgreementService implements ModifyUserAgreementServiceInterface
{
    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository,
        private readonly UserAgreementHelperInterface $helper,
        private readonly UserHelperInterface $userHelper,
        private readonly AgreementHelperInterface $agreementHelper,
        private readonly MergeService $mergeService,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {}

    public function __invoke(CommonModifyParams $userAgreementServiceDto): CommonModifyParams
    {
        $newUserAgreementData = $userAgreementServiceDto->read();
        $userAgreement = $this->helper->findUserAgreementForModify($newUserAgreementData);

        if (!isset($userAgreement) || !($userAgreement instanceof UserAgreement)) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt o id: %s nie istnieje w bazie danych.', $newUserAgreementData['id'])
            );
        }

        if(isset($newUserAgreementData['userId']) || isset($newUserAgreementData['userExternalId'])){
            $userExternalId = $newUserAgreementData['userExternalId'] ?? null;
            $userId = $newUserAgreementData['userId'] ?? null;
            $user = $this->userHelper->getUser($userId, $userExternalId);

            $newUserAgreementData['userId'] = $user->getId();
        }

        if(isset($newUserAgreementData['agreementId']) || isset($newUserAgreementData['agreementExternalId'])){
            $agreementExternalId = $newUserAgreementData['agreementExternalId'] ?? null;
            $agreementId = $newUserAgreementData['agreementId'] ?? null;
            $agreement = $this->agreementHelper->getAgreement($agreementId, $agreementExternalId);

            $newUserAgreementData['agreementId'] = $agreement->getId();
        }

        $this->mergeService->merge(
            $userAgreement,
            $newUserAgreementData,
            $userAgreementServiceDto->getMergeNestedObjects()
        );

        $userAgreement->validate();

        $this->eventsDispatcher->flushInternalEvents();
        $userAgreement = $this->repository->save($userAgreement);
        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonModifyParams())->write($userAgreement);

        return $resultDTO;
    }
}
