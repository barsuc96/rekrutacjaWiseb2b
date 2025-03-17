<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\CommonDetailsParams;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;
use Wise\User\Service\User\Interfaces\RegisterEmailConfirmServiceInterface;

/**
 * Serwis obsługuje potwierdzenie maila po rejestracji
 */
class RegisterEmailConfirmService implements RegisterEmailConfirmServiceInterface
{
    public const HASH_SEPARATOR = 'xaf32de6';

    public function __construct(
        private readonly ModifyUserServiceInterface $modifyUserService,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService
    ){}

    public function __invoke(CommonServiceDTO $commonServiceDTO): void
    {
        $data = $commonServiceDTO->read();

        // Pobranie danych z hasha
        $userData = $this->getHashData($data);

        // Modyfikacja użytkownika
        $this->modifyUserData($userData);
    }

    /**
     * Pobiera dane z hasha
     * @param array $data
     * @return array
     */
    protected function getHashData(array $data): array
    {
        if(empty($data['hash'])){
            throw (new CommonLogicException())->setTranslation('exceptions.user.hash_required');
        }

        $keyHash = self::HASH_SEPARATOR;

        // Dziele hash na części
        $exploded = explode($keyHash, $data['hash']);
        if((count($exploded) < 3)){
            throw (new CommonLogicException())->setTranslation('exceptions.user.hash_invalid');
        }

        // Wyodrębniam dane
        $userId = $exploded[1];
        $hash = $exploded[2];


        // Pobieram dane użytkownika
        $userParams = new CommonDetailsParams();
        $userParams
            ->setFilters([new QueryFilter('id', $userId)])
            ->setFields(['id' => 'id', 'email' => 'email']);
        $userData = ($this->getUserDetailsService)($userParams)->read();

        // przygotowanie hash
        $foundedHash = md5($userData['email'] . $userData['id']);

        // Sprawdzam czy hash jest poprawny
        if($foundedHash !== $hash){
            throw (new CommonLogicException())->setTranslation('exceptions.user.hash_invalid');
        }

        return $userData;
    }

    /**
     * Modyfikuje dane użytkownika
     * @param array $userData
     * @return void
     */
    protected function modifyUserData(array $userData): void
    {
        $params = new CommonModifyParams();
        $params
            ->writeAssociativeArray([
                'id' => intval($userData['id']),
                'mailConfirmed' => true
            ]);

        ($this->modifyUserService)($params);
    }
}
