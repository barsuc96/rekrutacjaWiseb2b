<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\ApiAdmin\Service;

use Codeception\Module\Symfony;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\Notifications\NotificationManager;
use Wise\Core\Notifications\NotificationResponseDTOConverterServiceInterface;
use Wise\Core\Repository\RepositoryManager;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\ServiceInterface\CoreAutoOverloginUserServiceInterface;
use Wise\Core\Tests\Unit\Helper\CommonApiShareMethodHelperTest;
use Wise\Core\Validator\ObjectValidator;

final class AdminApiShareMethodHelperTest extends CommonApiShareMethodHelperTest
{

    public function _before(): void
    {
        /** @var Symfony $symfony */
        $symfony = $this->getModule('Symfony');
        /** @var SerializerInterface $serializer */
        $serializer = $symfony->grabService(SerializerInterface::class);
        /** @var PropertyAccessorInterface $propertyAccessor */
        $propertyAccessor = $symfony->grabService(PropertyAccessorInterface::class);
        /** @var ObjectValidator $objectValidator */
        $objectValidator = $symfony->grabService(ObjectValidator::class);
        /** @var RepositoryManager $repositoryManager */
        $repositoryManager = $symfony->grabService(RepositoryManager::class);
        /** @var TranslatorInterface $translator */
        $translator = $symfony->grabService(TranslatorInterface::class);
        /** @var MergeService $mergeService */
        $mergeService = $symfony->grabService(MergeService::class);
        /** @var NotificationResponseDTOConverterServiceInterface $notificationManager */
        $notificationResponseDTOConverterService = $symfony->grabService(NotificationResponseDTOConverterServiceInterface::class);
        /** @var NotificationManager $notificationManager */
        $notificationManager = $symfony->grabService(NotificationManager::class);
        /** @var DomainEventsDispatcher $domainEventsDispatcher */
        $domainEventsDispatcher = $symfony->grabService(DomainEventsDispatcher::class);
        /** @var RequestUuidServiceInterface $domainEventsDispatcher */
        $requestUuidService = $symfony->grabService(RequestUuidServiceInterface::class);
        /** @var ReplicationServiceInterface $domainEventsDispatcher */
        $replicationService = $symfony->grabService(ReplicationServiceInterface::class);
        /** @var DenormalizerInterface $domainEventsDispatcher */
        $denormalizer = $symfony->grabService(DenormalizerInterface::class);
        /** @var CoreAutoOverloginUserServiceInterface $domainEventsDispatcher */
        $coreAutoOverloginUserServiceInterface= $symfony->grabService(CoreAutoOverloginUserServiceInterface::class);
        /** @var Stopwatch $stopwatch */
        $stopwatch = $symfony->grabService(Stopwatch::class);

        $this->sharedActionService = new AdminApiShareMethodsHelper(
            $serializer,
            $propertyAccessor,
            $objectValidator,
            $repositoryManager,
            $translator,
            $mergeService,
            $notificationResponseDTOConverterService,
            $notificationManager,
            $domainEventsDispatcher,
            $requestUuidService,
            $replicationService,
            $denormalizer,
            $coreAutoOverloginUserServiceInterface,
            $stopwatch
        );
    }
}
