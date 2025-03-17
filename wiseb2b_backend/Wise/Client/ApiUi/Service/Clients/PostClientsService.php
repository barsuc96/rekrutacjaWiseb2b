<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Wise\Client\ApiUi\Dto\AddressDto;
use Wise\Client\ApiUi\Dto\ClientRepresentativeDto;
use Wise\Client\ApiUi\Service\Clients\Interfaces\PostClientsServiceInterface;
use Wise\Client\Service\Client\Interfaces\AddClientServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Receiver\Service\Receiver\Interfaces\ReceiverHelperInterface;

class PostClientsService extends AbstractPostUiApiService implements PostClientsServiceInterface
{
    protected string $messageSuccessTranslation = 'client.created';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddClientServiceInterface $addClientService,
        private readonly ReceiverHelperInterface $receiverHelper
    ){
        parent::__construct($sharedActionService, $addClientService);
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @return CommonModifyParams
     * @throws \Exception
     */
    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $serviceDTO = parent::fillParams($dto);
        $data = $serviceDTO->read();
        $this->mapClientAddressFields($data, $dto);
        $this->mapClientRepresentativeFields($data, $dto);
        $serviceDTO->writeAssociativeArray($data);

        return $serviceDTO;
    }

    /**
     * Uzupełnienie danych adresowych klienta
     * @param array $data
     * @param AbstractDto $dto
     * @return void
     * @throws \Exception
     */
    protected function mapClientAddressFields(array &$data, AbstractDto $dto): void
    {
        if(empty($data['building']) && empty($data['apartment']) && empty($data['countryCode']) && empty($data['country']) && empty($data['state'])) {
            return;
        }
        $fields = (new AddressDto())->mergeWithMappedFields([]);
        $data['building'] = (isset($data['building']) && !empty($data['building'])) ?  $data['building'] : '';
        $data['apartment'] = (isset($data['apartment']) && !empty($data['apartment'])) ? $data['apartment'] : '';
        $data['countryCode'] = (isset($data['countryCode']) && !empty($data['countryCode'])) ?  $data['countryCode'] : $data['country'];
        $data['country'] = (isset($data['country']) && !empty($data['country'])) ?  $data['country'] : '';
        $data['state'] = (isset($data['state']) && !empty($data['state'])) ?  $data['state'] : '';
        $addressDto = $this->sharedActionService->prepareSingleObjectResponseDto(AddressDto::class, $data, $fields);

        $this->receiverHelper->validateCountryCode($addressDto->getCountryCode() ?? $addressDto->getCountry());

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
                    'name' => $dto->getName() ?? null,
                ]
            )
        );

        unset($data['building'], $data['apartment'], $data['city'], $data['postalCode'], $data['countryCode'], $data['country'], $data['state'], $data['street']);
        $data['registerAddress'] = $serviceDTO->read();
    }

    /**
     * Uzupełnienie danych reprezentujących klienta
     * @param array $data
     * @param AbstractDto $dto
     * @return void
     * @throws \Exception
     */
    protected function mapClientRepresentativeFields(array &$data, AbstractDto $dto): void
    {
        if(empty($data['contactPersonFirstName']) && empty($data['contactPersonLastName'])) {
            return;
        }

        // Tworze DTO danych reprezentujących klienta i merguje z nim dane z requesta
        // W związku, że pola z DTO nie zgadzają się z tymi z requesta wykorzystane zostało prepareSingleObjectResponseDto
        $fields = (new ClientRepresentativeDto())->mergeWithMappedFields([
            'personFirstname' => 'contactPersonFirstName',
            'personLastname' => 'contactPersonLastName',
        ]);

        $clientRepresentativeDto = $this->sharedActionService->prepareSingleObjectResponseDto(ClientRepresentativeDto::class, $data, $fields);

        // Umieszczam te dane w CommonModifyParams
        $serviceDTO = new CommonModifyParams();
        $serviceDTO->write($clientRepresentativeDto, []);

        // Dodaje brakujące dane
        unset($data['contactPersonFirstName'], $data['contactPersonLastName']);
        $data['clientRepresentative'] = $serviceDTO->read();
    }
}
