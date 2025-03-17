<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\AgreementHelperInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;
use Wise\User\Service\UserAgreement\Interfaces\AddUserAgreementServiceInterface;

class AddUserAgreementService implements AddUserAgreementServiceInterface
{
    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository,
        private readonly UserHelperInterface $userHelper,
        private readonly AgreementHelperInterface $agreementHelper,
        private readonly MergeService $mergeService,
        private readonly DomainEventsDispatcher $eventsDispatcher,
    ) {}

    public function __invoke(CommonModifyParams $userAgreementServiceDto): CommonModifyParams
    {
        $newUserAgreementData = $userAgreementServiceDto->read();
        $id = $newUserAgreementData['id'] ?? null;

        if ($this->repository->findOneBy(['id' => $id])) {
            throw new ObjectExistsException('Obiekt w bazie już istnieje. Id: ' . $id);
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

        $newUserAgreement = new UserAgreement();

        $this->mergeService->merge(
            $newUserAgreement,
            $newUserAgreementData,
            $userAgreementServiceDto->getMergeNestedObjects()
        );

        $newUserAgreement->validate();
        $this->eventsDispatcher->flushInternalEvents();
        $newUserAgreement = $this->repository->save($newUserAgreement);
        $this->eventsDispatcher->flush();

        ($resultDTO = new CommonModifyParams())->write($newUserAgreement);

        return $resultDTO;
    }
}
