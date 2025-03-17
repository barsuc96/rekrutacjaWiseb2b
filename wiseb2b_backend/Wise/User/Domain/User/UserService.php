<?php

declare(strict_types=1);

namespace Wise\User\Domain\User;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Core\Domain\AbstractEntityDomainService;
use Wise\Core\Domain\ShareMethodHelper\EntityDomainServiceShareMethodsHelper;
use Wise\Core\Helper\QueryFilter\QueryJoinsHelper;
use Wise\Core\Model\QueryJoin;
use Wise\Security\Service\CurrentUserService;
use Wise\User\Domain\Trader\Trader;

/**
 * Serwis domeny uzytkownika
 */
class UserService extends AbstractEntityDomainService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly EntityDomainServiceShareMethodsHelper $entityDomainServiceShareMethodsHelper,
        private readonly CurrentUserService $currentUserService
    ){
        parent::__construct(
            repository: $userRepository,
            notFoundException: UserNotFoundException::class,
            entityDomainServiceShareMethodsHelper: $entityDomainServiceShareMethodsHelper
        );
    }

    public function getRoleSymbol(int $roleId): string
    {
        return UserRoleEnum::from($roleId)->name;
    }

    /**
     * Metoda na podstawie wskazanych do wyciągnięcia pól ($fieldNames) przygotowuje joiny do zapytania
     */
    public function prepareJoins(?array $fieldsArray): array
    {
        $fieldsWhichRequireJoin = QueryJoinsHelper::prepareFieldsWhichRequireJoinsByFieldNames($fieldsArray);

        $joins = [];
        if (array_key_exists('traderId', $fieldsWhichRequireJoin)) {
            $joins[] = new QueryJoin(Trader::class, 'traderId', ['traderId' => 'traderId.id'], QueryJoin::JOIN_TYPE_LEFT);
        }

        $joins['clientId'] = new QueryJoin(Client::class, 'clientId', ['clientId' => 'clientId.id'], QueryJoin::JOIN_TYPE_LEFT);

        if (array_key_exists('clientGroupId', $fieldsWhichRequireJoin)) {
            $joins['clientGroupId'] = new QueryJoin(ClientGroup::class, 'clientGroupId', ['clientId.clientGroupId' => 'clientGroupId.id']);
        }

        return $joins;
    }

    public function getStreetWithNumber($street, $houseNumber): string
    {
        return ($street ?? '') . ' ' . ($houseNumber ?? '');
    }

    /**
     * Pobieramy dane użytkownika - name - stworzone z pól first_name i last_name
     */
    public function getName($firstName, $lastName): ?string
    {
        return $firstName. ' ' . $lastName;
    }

    /**
     * Weryfikuje czy przekazana rola jest wyższa od ról zalogowanego użytkownika
     * @param array $targetUserRoles Lista ról użytkownika do weryfikacji
     * @return bool
     * @throws EntityNotFoundException
     */
    public function isUserRolesHigher(array $targetUserRoles): bool
    {
        // Pobierz hierarchię ról z enuma i stwórz mapowanie wartości do indeksów
        $rolesHierarchy = UserRoleEnum::rolesByHierarchy();
        $roleValues = array_map(fn($roleEnum) => $roleEnum->value, $rolesHierarchy);
        $roleValueToIndex = array_flip($roleValues);

        // Znajdź najmniejszą wartość indeksu dla aktualnego użytkownika
        $currentUserRoleValues = $this->currentUserService->getRoles();
        $currentUserIndices = array_intersect_key($roleValueToIndex, array_flip($currentUserRoleValues));
        $lowestCurrentUserIndex = min($currentUserIndices);

        // Sprawdź czy którakolwiek z docelowych ról jest niższa lub równa najwyższej roli bieżącego użytkownika
        foreach ($targetUserRoles as $roleName) {
            $roleValue = array_search(UserRoleEnum::fromName($roleName)->value, $roleValues);
            if ($roleValue !== false && $roleValueToIndex[$roleValue] < $lowestCurrentUserIndex) {
                return true;
            }
        }

        return false;
    }


}
