<?php

declare(strict_types=1);

namespace Wise\User\Service\UserAgreement\DataProvider;

use Exception;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Wise\Core\DataProvider\AbstractAdditionalFieldProvider;
use Wise\Core\ServiceInterface\Locale\LocaleServiceInterface;
use Wise\User\Domain\Agreement\AgreementTranslation;
use Wise\User\Domain\Agreement\AgreementTranslationRepositoryInterface;

#[AutoconfigureTag(name: 'details_provider.user_agreement')]
class UserAgreementContentProvider extends AbstractAdditionalFieldProvider implements
    UserAgreementDetailsProviderInterface
{
    public const FIELD = 'content';

    public function __construct(

    ) {
    }

    /**
     * Ustawiamy pole content w zależności od agreement i języka
     *
     * @throws Exception
     */
    public function getFieldValue($entityId, ?array $cacheData = null): mixed
    {
//        $agreementTranslation = $this->repository->findOneBy([
//            'agreementId' => $cacheData['agreementId'],
//            'isActive' => true,
//            'language' => $this->localeService->getCurrentLanguage()
//        ]);
//
//        if ($agreementTranslation instanceof AgreementTranslation) {
//            return $agreementTranslation->getContent();
//        }

        return null;
    }
}
