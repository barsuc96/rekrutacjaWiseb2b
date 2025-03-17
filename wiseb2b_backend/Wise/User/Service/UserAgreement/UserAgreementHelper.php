<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement;

use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Service\Merge\MergeService;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;
use Wise\User\Service\UserAgreement\Interfaces\UserAgreementHelperInterface;

class UserAgreementHelper implements UserAgreementHelperInterface
{
    public function __construct(
        private readonly UserAgreementRepositoryInterface $repository,
        private readonly MergeService $mergeService,
    ) {}

    public function findUserAgreementForModify(array $data): ?UserAgreement
    {
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;
        $userAgreement = null;

        if (null !== $id) {
            $userAgreement = $this->repository->findOneBy(['id' => $id]);
            if (false === $userAgreement instanceof UserAgreement) {
                throw new ObjectNotFoundException('Nie znaleziono UserAgreement o id: ' . $id);
            }

            return $userAgreement;
        }

        if (null !== $idExternal) {
            $userAgreement = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        $userId = $data['userId'] ?? null;
        $agreementId = $data['agreementId'] ?? null;
        if ($userAgreement == null && $userId !== null && $agreementId !== null) {
            $userAgreement = $this->repository->findOneBy([
                'userId' => $userId,
                'agreementId' => $agreementId
            ]);
        }

        $clientId = $data['clientId'] ?? null;
        $agreementId = $data['agreementId'] ?? null;
        if ($userAgreement == null && $clientId !== null && $agreementId !== null) {
            $userAgreement = $this->repository->findOneBy([
                'clientId' => $clientId,
                'agreementId' => $agreementId
            ]);
        }

        return $userAgreement;
    }
}
