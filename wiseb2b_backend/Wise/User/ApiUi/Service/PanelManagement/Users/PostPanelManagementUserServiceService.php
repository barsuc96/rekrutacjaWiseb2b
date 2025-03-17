<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\CommonLogicException;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserServiceInterface;
use Wise\User\Service\User\Exceptions\PasswordException;
use Wise\User\Service\User\Interfaces\AddUserServiceInterface;

/**
 * Klasa obsługująca proces dodawania użytkownika
 * TODO: Dodać obsługę procesu dodawania użytkownika z ADRESEM
 */
class PostPanelManagementUserServiceService extends AbstractPostUiApiService  implements PostPanelManagementUserServiceInterface
{
    /**
     * Klucz translacji — zwracany, gdy proces się powiedzie
     * @var string
     */
    protected string $messageSuccessTranslation = 'user.success_create';

    /**
     * Czy do wyniku ma zostać dołączony wynik serwisu
     * @var bool
     */
    protected bool $attachServiceResultToResponse = true;

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly AddUserServiceInterface $addUserService,
    ){
        parent::__construct($sharedActionService, $addUserService);
    }

    /**
     * Metoda uzupełnia parametry dla serwisu
     * @param AbstractDto $dto
     * @return CommonModifyParams
     */
    protected function fillParams(AbstractDto $dto): CommonModifyParams
    {
        $serviceDTO = new CommonModifyParams();
        $serviceDTO->write($dto, $this->fieldMapping);
        $serviceDTO->setMergeNestedObjects(true);

        $data = $serviceDTO->read();

        if(empty($data['password'])){
            throw PasswordException::emptyPassword();
        }

        if(empty($data['repeatPassword'])){
            throw PasswordException::emptyPasswordConfirm();
        }

        if($data['repeatPassword'] !== $data['password']){
            throw PasswordException::notSame();
        }

        unset($data['repeatPassword']);

        if(
            isset($data['street']) ||
            isset($data['houseNumber']) ||
            isset($data['apartmentNumber']) ||
            isset($data['postalCode']) ||
            isset($data['city']) ||
            isset($data['countryCode']) ||
            isset($data['state']) ||
            isset($data['nameAddress'])
        ){
            $data['registerAddress'] = [
                'street' => $data['street'] ?? null,
                'houseNumber' => $data['houseNumber'] ?? null,
                'apartmentNumber' => $data['apartmentNumber'] ?? null,
                'postalCode' => $data['postalCode'] ?? null,
                'city' => $data['city'] ?? null,
                'countryCode' => $data['countryCode'] ?? null,
                'state' => $data['state'] ?? null,
                'name' => $data['nameAddress'] ?? null,
            ];
            unset($data['street'], $data['houseNumber'], $data['apartmentNumber'], $data['postalCode'], $data['city'], $data['countryCode'], $data['country'], $data['state'], $data['nameAddress']);
        }

        $serviceDTO->writeAssociativeArray($data);


        return $serviceDTO;
    }
}
