<?php

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Dto\AddressDto;
use Wise\Core\ApiUi\Dto\FieldInfoDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPutService;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\ApiUi\Dto\Users\PutUserProfileDto;
use Wise\User\ApiUi\Service\Interfaces\PutUserProfileServiceInterface;
use Webmozart\Assert\Assert;
use Wise\Core\Dto\AbstractDto;
use Wise\User\Service\User\Interfaces\ModifyUserProfileServiceInterface;

class PutUserProfileService extends AbstractPutService implements PutUserProfileServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        protected readonly TranslatorInterface $translator,
        protected readonly ModifyUserProfileServiceInterface $service,
        protected readonly CurrentUserServiceInterface $currentUserService,
    )
    {
        parent::__construct($sharedActionService);
    }

    public function put(AbstractDto $dto): void
    {
        /**
         * @var PutUserProfileDto $dto
         */
        Assert::isInstanceOf($dto, PutUserProfileDto::class);

        // Przygotowanie danych
        $params = new CommonModifyParams();
        $params->write($dto, [
            'userId' => 'id'
        ]);
        $data = $params->read();

        if(!empty($data['customer'])){
            $this->mapClientFields($data, $dto);
            $this->mapClientAddressFields($data, $dto);
        }

        $params->writeAssociativeArray($data);

        try{
            // Modyfikacja
            ($this->service)($params);
            $this->setParameters($this->translator->trans('success.update_message'))->setData([]);

        }catch (InvalidInputArgumentException $e) {
            $this->setParameters(
                message: $this->translator->trans('error.basic_message'),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setInvalidValue($data['email'])
                    ->setMessage($e->getMessage())
            ]);
        }catch (ObjectNotFoundException $ee) {
            $this->setParameters(
                message: $this->translator->trans('error.basic_message'),
                messageStyle: ResponseMessageStyle::FAILED->value
            );
            $this->setFieldInfos([
                (new FieldInfoDto())
                    ->setMessage($ee->getMessage())
            ]);
        }

    }

    /**
     * Uzupełnienie danych klienta
     * Konwersja do poprawnej obsługi przez serwis
     * @param array $data
     * @param PutUserProfileDto $dto
     * @return void
     * @throws \Exception
     */
    protected function mapClientFields(array &$data, PutUserProfileDto $dto): void
    {
        $data['customer']['id'] = $this->currentUserService->getClientId($dto->getUserId());
    }

    /**
     * Uzupełnienie danych adresowych klienta
     * Konwersja do poprawnej obsługi przez serwis
     * @param array $data
     * @param PutUserProfileDto $dto
     * @return void
     * @throws \Exception
     */
    protected function mapClientAddressFields(array &$data, PutUserProfileDto $dto): void
    {
        $addressData = $data['customer']['address'] ?? null;
        $addressData['state'] = null;

        if(!empty($addressData)) {
            $fields = (new AddressDto())->mergeWithMappedFields([]);
            $addressDto = $this->sharedActionService->prepareSingleObjectResponseDto(AddressDto::class, $addressData, $fields);

            // Umieszczam te dane w CommonModifyParams
            $serviceDTO = new CommonModifyParams();
            $serviceDTO->write($addressDto, [
                'country' => 'countryCode',
                'building' => 'houseNumber',
                'apartment' => 'apartmentNumber',
            ]);

            // Uzupełniam o brakujący name
            $serviceDTO->writeAssociativeArray(
                array_merge(
                    $serviceDTO->read(),
                    [
                        'name' => $dto->getCustomer()->getName() ?? null,
                    ]
                )
            );

            unset($data['customer']['address']);
            $data['customer']['registerAddress'] = $serviceDTO->read();
        }
    }
}
