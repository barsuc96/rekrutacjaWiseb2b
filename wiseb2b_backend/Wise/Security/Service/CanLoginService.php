<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Wise\Client\Domain\Client\Exceptions\ClientException;
use Wise\Client\Domain\Client\Exceptions\ClientNotFoundException;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\Security\Service\Interfaces\CanLoginServiceInterface;
use Wise\User\Domain\User\Exceptions\UserException;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

/**
 * Serwis odpowiedzialny za weryfikacje logowanie użytkownika
 * Zwraca false gdy, z jakiegoś powodu użytkownik nie może się zalogować
 */
class CanLoginService implements CanLoginServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService
    ){}

    public function __invoke(CommonServiceDTO $dto): bool
    {
        $data = $dto->read();

        if(empty($data['username']) || empty($data['password'])){
            return false;
        }

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['login' => $data['username']]);
        if(!$user){
            return false;
        }

        if($user->getIsActive() !== true){
            return false;
        }

        return true;
    }

    /**
     * Walidacja czy użytkownik może się zalogować po zalogowaniu przez oAuth (czyli dane logowania są poprawne)
     * @param string $login
     * @return void
     */
    public function validateAfterLogin(string $login): void
    {
        // Walidacja użytkownika
        $user = $this->getUserData($login);
        if(empty($user)){
            throw new UserNotExistException();
        }

        if(empty($user['isActive'])){
            throw UserException::incorrectLoginData();
        }

        if(empty($user['mailConfirmed'])){
            throw UserException::mailConfirmedFalse();
        }


        // Walidacja klienta
        $client = $this->getClientData($user['clientId']);
        if(empty($client)){
            throw new ClientNotFoundException();
        }

        if(empty($client['isActive'])){
            throw UserException::incorrectLoginData();
        }

        if($client['status'] === 0){
            throw ClientException::notAccepted();
        }
    }

    /**
     * Zwraca szczegóły użytkownika
     * @param string $login
     * @return array
     */
    protected function getUserData(string $login): array
    {
        $params = new CommonDetailsParams();
        $params
            ->setFilters([
                new QueryFilter('login', $login)
            ])
            ->setFields([
                'id' => 'id',
                'clientId' => 'clientId',
                'login' => 'login',
                'isActive' => 'isActive',
                'mailConfirmed' => 'mailConfirmed',
            ]);

        return ($this->getUserDetailsService)($params)->read();
    }

    /**
     * Zwraca szczegóły klienta
     * @param int $clientId
     * @return array
     */
    protected function getClientData(int $clientId): array
    {
        $params = new CommonDetailsParams();
        $params
            ->setFilters([
                new QueryFilter('id', $clientId)
            ])
            ->setFields([
                'id' => 'id',
                'name' => 'name',
                'isActive' => 'isActive',
                'status' => 'status',
            ]);

        return ($this->getClientDetailsService)($params)->read();
    }
}
