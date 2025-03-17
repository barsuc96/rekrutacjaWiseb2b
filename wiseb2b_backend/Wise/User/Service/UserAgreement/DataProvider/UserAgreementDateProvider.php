<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user_agreement')]
class UserAgreementDateProvider extends AbstractAdditionalFieldProvider implements UserAgreementDetailsProviderInterface
{
    public const FIELD = 'date';

    public function __construct(
        private readonly UserAgreementRepositoryInterface $userAgreementRepository,
    ) {}

    /**
     * Ustawiamy pole date w zależności od tego czy użytkownik zakceptował zgody, czy nie
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        if ($cacheData['userAgreementId'] === null) {
            return null;
        }

        /** @var UserAgreement $userAgreement */
        $userAgreement = $this->userAgreementRepository->find($cacheData['userAgreementId']);

        return $userAgreement->getAgreeDate() ?? $userAgreement->getDisagreeDate() ?? null;
    }
}
