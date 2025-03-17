<?php

declare(strict_types=1);

namespace Wise\Receiver\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;
use Wise\Core\ApiUi\ServiceInterface\ApiUiPostServiceInterface;
use Wise\Cart\ApiUi\Dto\Carts\PostCartsRequestDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Service\Interfaces\ConfigServiceInterface;
use Wise\Receiver\ApiUi\Dto\PutReceiversRequestDto;
use Wise\Receiver\ApiUi\Service\Interfaces\PutReceiversServiceInterface;
use Wise\Receiver\Domain\Receiver\Exceptions\AccessToModifyReceiverDisabledException;
use Wise\Receiver\Service\Receiver\Interfaces\ModifyReceiverServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;
use Wise\Receiver\WiseReceiverExtension;

/** @implements ApiUiPostServiceInterface<PostCartsRequestDto> */
class PutReceiversService extends AbstractPutService implements PutReceiversServiceInterface
{
    public function __construct(
        protected UiApiShareMethodsHelper $sharedActionService,
        private readonly TranslatorInterface $translator,
        private readonly ModifyReceiverServiceInterface $service,
        private readonly ReceiverHelperInterface $receiverHelper,
        private readonly ConfigServiceInterface $configService
    ) {
        parent::__construct($sharedActionService);
    }
    /**
     * @throws \ReflectionException
     */
    public function put(PutReceiversRequestDto|AbstractDto $dto): void
    {
        Assert::isInstanceOf($dto, PutReceiversRequestDto::class);

        // Weryfikacja czy można zaktualizować odbiorcę
        $this->accessToModifyReceiver($dto);

        if($dto->isInitialized('address') && $dto->getAddress() !== null){
            $this->receiverHelper->validateCountryCode($dto?->getAddress()?->getCountryCode());
        }

        $this->sharedActionService->notificationManager->fillCustomPropertyPath([
            'receivdsfs.sdfsdf.sdf' => 'delivery_address.postal_code'
        ]);

        $this->serviceDtoWrite($dto, [
            'receiverId' => 'id',
            'address.houseNumber' => 'address.building',
            'address.apartmentNumber' => 'address.apartment',
            'address.countryCode' => 'address.country',
            'address' => 'deliveryAddress'
        ]);

//        ($serviceParams = new CommonModifyParams())->write($dto, [
//            'receiverId' => 'id',
//            'address.houseNumber' => 'address.building',
//            'address.apartmentNumber' => 'address.apartment',
//            'address.countryCode' => 'address.country',
//            'address' => 'deliveryAddress'
//        ]);
        $data = $this->serviceDto->read();
        $this->mapClientAddressFields($data);
        $this->serviceDto->writeAssociativeArray($data);

        $this->serviceDto->setMergeNestedObjects(false);

        $resultServiceDto = ($this->service)($this->serviceDto);
        $fieldMapping = [
            "deliveryAddress" => "address",
            "firstName" => "first_name",
            "lastName" => "last_name"
        ];
        $this
            ->setParameters($this->translator->trans('receiver.modified'),'success')
            ->setData(
                $resultServiceDto
                ->read($fieldMapping)
            );
    }

    protected function mapClientAddressFields(array &$data): void
    {
        if(!empty($data['address']['country'])){
            $data['address']['countryCode'] = $data['address']['countryCode'] ?? $data['address']['country'];
            unset($data['address']['country']);
        }
    }

    /**
     * Weryfikacja czy można dodać odbiorcę
     * @param PutReceiversRequestDto|AbstractDto $dto
     * @return void
     */
    protected function accessToModifyReceiver(PutReceiversRequestDto|AbstractDto $dto): void
    {
        $config = $this->configService->get(key: WiseReceiverExtension::getExtensionAlias(), returnOnlyCurrentStoreConfigWithoutRegularConfig: true)['can_modify_receiver'] ?? null;
        if($config === null){
            $config = $this->configService->get(key: WiseReceiverExtension::getExtensionAlias())['can_modify_receiver'] ?? false;
        }

        if(!$config){
            throw new AccessToModifyReceiverDisabledException();
        }

    }
}
