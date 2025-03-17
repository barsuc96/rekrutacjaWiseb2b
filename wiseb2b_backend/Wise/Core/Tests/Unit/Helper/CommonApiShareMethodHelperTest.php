<?php

declare(strict_types=1);

namespace Wise\Core\Tests\Unit\Helper;

use Codeception\Module\Symfony;
use Codeception\Test\Unit;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiAdmin\ServiceInterface\RequestUuidServiceInterface;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Notifications\NotificationManager;
use Wise\Core\Notifications\NotificationResponseDTOConverterServiceInterface;
use Wise\Core\Repository\RepositoryManager;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Interfaces\Admin\ReplicationServiceInterface;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\ServiceInterface\CoreAutoOverloginUserServiceInterface;
use Wise\Core\Tests\Unit\Helper\Stubs\Phone;
use Wise\Core\Tests\Unit\Helper\Stubs\SingleItemResponseDto;
use Wise\Core\Validator\ObjectValidator;

class CommonApiShareMethodHelperTest extends Unit
{
    protected CommonApiShareMethodsHelper $sharedActionService;

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

        $this->sharedActionService = new CommonApiShareMethodsHelper(
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

    public function testFieldMappingForFlatItemsWorkingCorrectly(): void
    {
        $array = [
            'id' => 1,
            'name' => 'Test product',
        ];

        $fieldsMapping = [
            'ref' => 'id',
            'newName' => 'name'
        ];

        $mappedArray = $this->sharedActionService->applyFieldsMappingToArray($array, $fieldsMapping);

        $this->assertSame(array_keys($mappedArray), array_keys($fieldsMapping));
    }

    /**
     * @throws \Exception
     */
    public function testPrepareResponseDtoCanNotOperateOnNonResponseObjects(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->sharedActionService->prepareSingleObjectResponseDto(Phone::class, [], []);
    }

    /**
     * @throws \Exception
     */
    public function testPrepareResponseDtoForMultipleItems(): void
    {
        $actual = $this->sharedActionService->prepareMultipleObjectsResponseDto(
            SingleItemResponseDto::class,
            [
                [
                    'id' => 1,
                    'name' => 'Test',
                    'strings' => ['a', 'b', 'c'],
                    'phones' => [['element' => '123456789']],
                    'sysInsertTime' => new \DateTime('2023-06-27 10:03:36'),
                ],
                [
                    'id' => 2,
                    'name' => 'Test2',
                    'strings' => ['d', 'e', 'f'],
                    'phones' => [['element' => '123456789'], ['element' => '987654321']],
                    'sysInsertTime' => new \DateTime('2023-06-30 13:47:36'),
                ],
            ],
            [
                'id' => 'id',
                'name' => 'name',
                'strings' => 'strings',
                'phones.[].number' => 'phones.[].element',
                'createdAt' => 'sysInsertTime',
            ],
        );

        $this->assertIsArray($actual);
        $this->assertCount(2, $actual);
        foreach ($actual as $item) {
            $this->assertInstanceOf(SingleItemResponseDto::class, $item);
        }
    }

    public function testFieldMappingSubelementForElementWorkingCorrectly(): void
    {
        $array = [
            'unit.name' => 'Jednostka 1'
        ];

        $fieldsMapping = [
            'newName' => 'unit.name',
        ];

        $mappedArray = $this->sharedActionService->applyFieldsMappingToArray($array, $fieldsMapping);

        $this->assertIsArray($mappedArray);
        $this->assertSame($mappedArray['newName'], $array['unit.name']);
    }
}
