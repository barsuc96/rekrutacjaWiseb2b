<?php

declare(strict_types=1);

namespace Wise\User\Service\User\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\User\Domain\Agreement\Agreement;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Domain\UserAgreement\UserAgreement;
use Wise\User\Domain\UserAgreement\UserAgreementRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user')]
class UserAgreementRequiredProvider extends AbstractAdditionalFieldProvider implements UserDetailsProviderInterface
{
    public const FIELD = 'consentsRequired';

    public function __construct(
        private readonly AgreementRepositoryInterface $agreementRepository,
        private readonly UserAgreementRepositoryInterface $userAgreementRepository,
    ) {}

    /**
     * Tutaj sprawdzamy czy użytkownik wyraził zgody na wszystkie obowiązkowe Agreementy
     *
     * @throws Exception
     */
    public function getFieldValue($userId, ?array $cacheData = null): mixed
    {
        $agreements = $this->agreementRepository->findBy(['isActive' => true]);
        $userAgreements = $this->userAgreementRepository->findBy(['userId' => $userId, 'isActive' => true]);

        /** @var Agreement $agreement */
        foreach ($agreements as $agreement) {
            if ($agreement->getIsRequired()) {
                /** @var UserAgreement $userAgreement */
                foreach ($userAgreements as $userAgreement) {
                    if ($userAgreement->getIsActive() === true &&
                        $userAgreement->getAgreementId() === $agreement->getId()
                    ) {
                        continue 2;
                    }
                }

                return true;
            }
        }

        return false;
    }
}
