<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Core\Exception\InvalidInputArgumentException;
use Wise\Core\Exception\ObjectNotFoundException;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonListParams;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\Trader\TraderRepositoryInterface;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\ListUsersServiceInterface;
use Wise\User\Service\User\Interfaces\UserHelperInterface;

class UserHelper implements UserHelperInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly ClientRepositoryInterface $clientRepository,
        private readonly TraderRepositoryInterface $traderRepository,
        private readonly ListUsersServiceInterface $listUsersService
    ) {
    }

    public function findUserForModify(array $data): ?User
    {
        $user = null;
        $id = $data['id'] ?? null;
        $idExternal = $data['idExternal'] ?? null;

        if (null !== $id) {
            $user = $this->repository->findOneBy(['id' => $id]);
            if (false === $user instanceof User) {
                throw new ObjectNotFoundException('Nie znaleziono User o id: ' . $id);
            }

            return $user;
        }

        if (null !== $idExternal) {
            $user = $this->repository->findOneBy(['idExternal' => $idExternal]);
        }

        return $user;
    }

    public function getUser(?int $id, ?string $externalId): User
    {
        $user = null;

        if (null !== $id) {
            $user = $this->repository->findOneBy(['id' => $id]);
        } elseif (null !== $externalId) {
            $user = $this->repository->findOneBy(['idExternal' => $externalId]);
        }

        if (false === $user instanceof User) {
            throw new ObjectNotFoundException(
                sprintf('Obiekt User nie istnieje. Id: %s, externalId: %s', $id, $externalId)
            );
        }

        return $user;
    }

    public function getClient(array $data): ?Client
    {
        $clientId = $data['clientId'] ?? null;
        $clientExternalId = $data['clientExternalId'] ?? null;
        $client = null;

        if (null !== $clientId) {
            $client = $this->clientRepository->findOneBy(['id' => $clientId]);
        } elseif (null !== $clientExternalId) {
            $client = $this->clientRepository->findOneBy(['idExternal' => $clientExternalId]);
            if (is_null($client)) {
                $client = (new Client())->setIsActive(false)->setIdExternal($clientExternalId);
                $client = $this->traderRepository->save($client);
            }
        }

        return $client instanceof Client === true ? $client : null;
    }

    public function getRole(array $data)
    {
        // TODO: Implement getRole() method.
    }

    public function getTrader(array $data): ?Trader
    {
        $traderId = $data['traderId'] ?? null;
        $traderExternalId = $data['traderExternalId'] ?? null;
        $trader = null;

        if (null !== $traderId) {
            $trader = $this->traderRepository->findOneBy(['id' => $traderId]);
        } elseif (null !== $traderExternalId) {
            $trader = $this->traderRepository->findOneBy(['idExternal' => $traderExternalId]);
            if (is_null($trader)) {
                $trader = (new Trader())->setIsActive(false)->setIdExternal($traderExternalId);
                $trader = $this->traderRepository->save($trader);
            }
        }

        return $trader instanceof Trader === true ? $trader : null;
    }

    public function getAllUsersForClient(int $clientId): array
    {
        return $this->repository->findBy(['clientId' => $clientId]);
    }

    /**
     * Weryfikacja czy przekazany email może zostać zapisany przez użytkownika
     * @param array $data
     * @param string|null $newEmail
     * @return bool
     * @throws ObjectNotFoundException
     */
    public function isValidEmailToUseByUser(array $data, ?string $newEmail = null): bool
    {
        if($newEmail === null){
            return true;
        }

        $user = $this->findUserForModify($data);

        if ($user->getEmail() === $newEmail) {
            return true;
        }

        if(!$this->repository->findOneBy(['email' => $newEmail])){
            return true;
        }

        return false;
    }

    public function checkUserExists(int $id = null, string $idExternal = null): bool
    {
        $userExist = false;

        if($id !== null){
            $userExist = $this->repository->isExists(['id' => $id]);
        }

        if($idExternal !== null && $userExist === false){
            $userExist = $this->repository->isExists(['idExternal' => $idExternal]);
        }

        if(!$userExist){
            throw new ObjectNotFoundException('Nie znaleziono użytkownika');
        }

        return true;
    }

    public function getUserIdIfExistsByData(?int $id = null, ?array $userData = null): ?int
    {
        $user = null;

        // Pobranie na podstawie przekazanego id
        if($id !== null){
            $params = new CommonListParams();
            $params
                ->setFilters([
                    new QueryFilter('id', $id)
                ])
                ->setFields([]);
            $user = ($this->listUsersService)($params)->read();
        }

        // Jeśli nie znaleziono na podstawie id, zrób to na podstawie $deliveryMethodData
        if(empty($user) && !empty($userData)){
            $filters = [];

            // Przygotowanie filtrów na podstawie danych
            foreach ($userData as $field => $value){
                $filters[] = new QueryFilter($field, $value);
            }

            $params = new CommonListParams();
            $params
                ->setFilters($filters)
                ->setFields([]);
            $user = ($this->listUsersService)($params)->read();
        }

        if(empty($user)){
            throw new UserNotExistException();
        }

        if(count($user) > 1){
            throw new InvalidInputArgumentException('Znaleziono więcej niż jeden obiekt DeliveryMethod');
        }

        $user = reset($user);

        // Weryfikacja czy dane pasują do siebie
        if(!empty($userData)){
            foreach ($userData as $field => $value){
                if(isset($user[$field]) && $user[$field] !== $value){
                    throw new ObjectValidationException('Znaleziono obiekt User lecz dane przekazane w request nie zą zbierzne z pobranym obiektem. Pole: ' . $field . '(' . $user[$field]  . ' => ' . $value . ' )');
                }
            }
        }

        return $user['id'];
    }
}
