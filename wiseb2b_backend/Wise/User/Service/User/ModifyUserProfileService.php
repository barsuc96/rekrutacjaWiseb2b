<?php

namespace Wise\User\Service\User;

use Wise\Client\Service\Client\Interfaces\ModifyClientServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserProfileServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;

/**
 * Serwis modyfikacji danych użytkownika przygotowany dla akcji po stronie UiApi
 */
class ModifyUserProfileService implements ModifyUserProfileServiceInterface
{
    public function __construct(
        protected readonly ModifyUserServiceInterface $modifyUserService,
        protected readonly ModifyClientServiceInterface $modifyClientService,
        protected readonly GetUserDetailsServiceInterface $userDetailsService,
        protected readonly UserHelperInterface $userHelper
    ) {
    }

    public function __invoke(CommonModifyParams $params): void
    {
        // Pobranie danych z DTO
        $data = $params->read();

        // Modyfikacja użytkownika
        $this->modifyUser($data);

        // Modyfikacja Klienta
        $this->modifyClient($data);
    }

    /**
     * Modyfikacja danych użytkownika
     * @param array $data
     * @return array
     */
    public function modifyUser(array $data): void
    {
        if ($this->userHelper->isValidEmailToUseByUser($data, $data['email'])) {
            $serviceDto = new CommonModifyParams();
            $serviceDto->writeAssociativeArray($data);

            ($this->modifyUserService)($serviceDto);
        } else {
            throw new InvalidInputArgumentException('Nie można zmodyfikować adresu email');
        }
    }

    /**
     * Modyfikacja danych klienta
     * @param array $data
     * @return array
     */
    public function modifyClient(array $data): void
    {
        $serviceDto = new CommonModifyParams();

        if(!empty($data['customer']['nip'])){
            $data['customer']['taxNumber'] = $data['customer']['nip'];
            unset($data['customer']['nip']);
        }

        $serviceDto->writeAssociativeArray($data['customer']);

        ($this->modifyClientService)($serviceDto);
    }
}