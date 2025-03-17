<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user_agreement')]
class UserAgreementNecessaryProvider extends AbstractAdditionalFieldProvider implements UserAgreementDetailsProviderInterface
{
    public const FIELD = 'necessary';

    public function __construct(
        private readonly AgreementRepositoryInterface $repository,
    ) {}

    /**
     * Ustawiamy pole ipAddress w zależności od tego czy użytkownik zakceptował zgody, czy nie
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
        $agreement = $this->repository->find($cacheData['agreementId']);

        if ($agreement instanceof Agreement) {
            return $agreement->getIsRequired();
        }

        return null;
    }
}
