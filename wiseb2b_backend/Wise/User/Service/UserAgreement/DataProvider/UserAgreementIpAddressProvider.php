<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user_agreement')]
class UserAgreementIpAddressProvider extends AbstractAdditionalFieldProvider implements UserAgreementDetailsProviderInterface
{
    public const FIELD = 'ipAddress';

    public function __construct(
        private readonly UserAgreementRepositoryInterface $userAgreementRepository,
    ) {}

    /**
     * Ustawiamy pole ipAddress w zależności od tego czy użytkownik zakceptował zgody, czy nie
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

        if ($userAgreement->getAgreeDate() !== null) {
            return $userAgreement->getAgreeIp();
        }

        if ($userAgreement->getDisagreeDate() !== null) {
            return $userAgreement->getDisagreeIp();
        }

        return null;
    }
}
