<?php

namespace Wise\Receiver\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Dto\AddressDto;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\DataTransformer\CommonDataTransformer;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Receiver\ApiUi\Dto\PostReceiversDto;
use Wise\Receiver\ApiUi\Service\Interfaces\PostReceiversServiceInterface;
use Wise\Receiver\Domain\Receiver\Exceptions\AccessToAddReceiverDisabledException;
use Wise\Receiver\Service\Receiver\Interfaces\AddReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;
use Wise\Receiver\WiseReceiverExtension;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;

class PostReceiversService extends AbstractPostService implements PostReceiversServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        NotificationManagerInterface $notificationManager,
        protected readonly TranslatorInterface $translator,
        protected readonly AddReceiverServiceInterface $service,
        protected readonly CurrentUserServiceInterface $currentUserService,
        private readonly ReceiverHelperInterface $receiverHelper,
        private readonly ConfigServiceInterface $configService
    ) {
        parent::__construct($sharedActionService, $notificationManager);
    }

    /** @var PostReceiversDto $dto */
    public function post(CommonPostUiApiDto $dto): void
    {
        // Weryfikacja czy można dodać odbiorcę
        $this->accessToAddReceiver($dto);

        if($dto->isInitialized('address') && $dto->getAddress() !== null){
            $this->receiverHelper->validateCountryCode($dto?->getAddress()?->getCountryCode());
        }

        //deliveryAddress by było potrzebne
        $this->serviceDtoWrite($dto, [
            'address.houseNumber' => 'address.building',
            'address.apartmentNumber' => 'address.apartment',
            'address.countryCode' => 'address.country',
            'address' => 'deliveryAddress'
        ]);

        $data = $this->serviceDto->read();
        $data['deliveryAddress']['name'] = $dto->getName();

        $this->serviceDto->writeAssociativeArray(array_merge(
            $data, [
                'clientId' => $this->currentUserService->getClientId(),
            ]
        ));

        $this->serviceDto->setMergeNestedObjects(true);

        $resultServiceDto = ($this->service)($this->serviceDto);
        $fieldMapping = [

            "deliveryAddress" => "address",
            "firstName" => "first_name",
            "lastName" => "last_name"
        ];
        $this
            ->setParameters($this->translator->trans('receiver.created'),'success')
            ->setData($resultServiceDto->read($fieldMapping));
    }

    /**
     * Weryfikacja czy można dodać odbiorcę
     * @param CommonPostUiApiDto|PostReceiversDto $dto
     * @return void
     */
    protected function accessToAddReceiver(CommonPostUiApiDto|PostReceiversDto $dto): void
    {
        $config = $this->configService->get(key: WiseReceiverExtension::getExtensionAlias(), returnOnlyCurrentStoreConfigWithoutRegularConfig: true)['can_add_receiver'] ?? null;
        if($config === null){
            $config = $this->configService->get(key: WiseReceiverExtension::getExtensionAlias())['can_add_receiver'] ?? false;
        }

        if(!$config){
            throw new AccessToAddReceiverDisabledException();
        }

    }
}
