<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use Symfony\Component\HttpFoundation\ParameterBag;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetService;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\Security\ApiUi\Dto\OverloginUserResponseDto;
use Wise\Security\ApiUi\Dto\OverloginUsersResponseDto;
use Wise\Security\ApiUi\Service\Interfaces\GetOverloginUsersServiceInterface;
use Wise\Security\Service\Interfaces\ListOverloginUsersForCurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Domain\User\UserServiceInterface;

/**
 * Serwis api - do pobrania listy użytkowników, na któych zalogowany użytkownik może się przelogować.
 */
class GetOverloginUsersService extends AbstractGetService implements GetOverloginUsersServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $shareMethodsHelper,
        private readonly ListOverloginUsersForCurrentUserServiceInterface $listOverloginUsersForCurrentUserService,
        private readonly UserServiceInterface $userService,
    ) {
        parent::__construct($shareMethodsHelper);
    }

    public function get(ParameterBag $parameters): array
    {
        $filters = [
            new QueryFilter('limit', null),
            new QueryFilter('isActive', true),
            new QueryFilter('roleId', [UserRoleEnum::ROLE_USER_MAIN->value, UserRoleEnum::ROLE_USER->value], QueryFilter::COMPARATOR_IN),
        ];
        $fields = [
            'id' => 'clientId.id',
            'name' => 'clientId.name',
            'clientIdExternal' => 'clientId.idExternal',
            'clientTaxNumber' => 'clientId.taxNumber',
            'userIdExternal' => 'idExternal',
            'userId' => 'id',
            'firstName' => 'firstName',
            'lastName' => 'lastName',
            'login' => 'login',
            'clientGroupId.storeId' => 'clientGroupId.storeId',
        ];

        $fields = (new OverloginUsersResponseDto())->mergeWithMappedFields($fields);

        /**
         * Przekazanie parametrów do serwisu
         */
        $params = new CommonListParams();

        $params
            ->setFilters($filters)
            ->setFields($fields)
            ->setFetchTotalCount();

        $serviceDto = ($this->listOverloginUsersForCurrentUserService)($params);
        $serviceDtoData = $serviceDto->read();
        $serviceDtoData = $this->filterElements($serviceDtoData, $parameters->get('searchKeyword') ?? null, $parameters->all());
        $this->setTotalCount($serviceDtoData['totalCount']);
        $serviceDtoData = $serviceDtoData['elements'];

        $arrayDto = (new OverloginUsersResponseDto())->fillArrayWithObjectMappedFields($serviceDtoData, $fields);

        return $this->fillOverloginUsersData($arrayDto, $serviceDtoData);
    }

    /**
     * Metoda służy do stworzenia listy klientów z listą użytkowników przypisanych do klientów, z płaskiej listy
     * użytkowników
     */
    protected function fillOverloginUsersData(
        $arrayDto,
        array $serviceDtoData
    ): array {
        $response = [];
        /** @var OverloginUsersResponseDto $overloginUsersResponseDto */
        foreach ($arrayDto as $key => $overloginUsersResponseDto) {
            $data = $serviceDtoData[$key];
            /**
             * Jeśli klient z $overloginUsersResponseDto jest taki sam jak z $serviceDtoData[$key],
             * to dodajemy dane uzytkownika do tego klienta
             */
            if ($overloginUsersResponseDto->getId() === $data['clientId_id']) {
                /**
                 * Tworzymy obiekt dla danych użytkownika
                 */
                $overloginUserResponseDto = new OverloginUserResponseDto();

                $overloginUserResponseDto
                    ->setId($data['id'])
                    ->setName($this->userService->getName($data['firstName'], $data['lastName']))
                    ->setLogin($data['login'])
                ;

                $responseDto = $overloginUsersResponseDto;

                /**
                 * Jeśli w tablicy $response nie ma jeszcze klienta o takim id, to dodajemy go do tablicy
                 */
                if (($response[$overloginUsersResponseDto->getId()] ?? null) === null) {
                    $response[$overloginUsersResponseDto->getId()] = $responseDto;
                } else {
                    $responseDto = $response[$overloginUsersResponseDto->getId()];
                }

                /**
                 * Dodajemy użytkownika do listy klienta
                 */
                $responseDto->addUserToList($overloginUserResponseDto);
            }
        }

        return $response;
    }

    /**
     * Metoda służy do filtrowania elementów
     */
    protected function filterElements(?array $serviceDtoData, ?string $searchKeyword, array $parameters): array
    {
        $positions = $serviceDtoData;
        $page = isset($parameters['page']) ? (int) $parameters['page'] : null;
        $limit = isset($parameters['limit']) ? (int) $parameters['limit'] : null;

        if($searchKeyword !== null ){
            $positions = array_filter($serviceDtoData, function($element) use($searchKeyword) {
                return
                    (!empty($element['idExternal']) && str_contains(strtolower(strval($element['idExternal'])), strtolower($searchKeyword))) ||
                    (!empty($element['clientId_idExternal']) && str_contains(strtolower(strval($element['clientId_idExternal'])), strtolower($searchKeyword))) ||
                    (!empty($element['firstName']) && str_contains(strtolower(strval($element['firstName'])), strtolower($searchKeyword))) ||
                    (!empty($element['lastName']) && str_contains(strtolower(strval($element['lastName'])), strtolower($searchKeyword))) ||
                    (!empty($element['clientId_taxNumber']) && str_contains(strtolower(strval($element['clientId_taxNumber'])), strtolower($searchKeyword))) ||
                    str_contains(strtolower($element['clientId_name']), strtolower($searchKeyword)) ||
                    str_contains(strtolower($element['login']), strtolower($searchKeyword)) ||
                    str_contains(strtolower(strval($element['id'])), strtolower($searchKeyword)) ||
                    str_contains(strtolower(strval($element['clientId_id'])), strtolower($searchKeyword));
            });
        }

        $currentCountElements = count($positions);

        // Jeśli podano page i limit, a ilość pozycji jest większa od limitu to przycinam tablicę (stronicowanie)
        if($page !== null && $limit !== null && $currentCountElements !== 0 && $currentCountElements > $limit ){
            $offset = ($page - 1) * $limit;
            $positions = array_slice($positions, $offset, $limit);
        }

        return [
            'elements' => array_values($positions),
            'totalCount' => $currentCountElements
        ];
    }
}
