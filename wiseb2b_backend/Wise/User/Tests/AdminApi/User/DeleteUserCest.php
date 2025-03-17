<?php

namespace Wise\User\Tests\AdminApi\User;

use Codeception\Util\HttpCode;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\User\ApiAdmin\Dto\Users\PutUsersDto;
use Wise\User\ApiAdmin\Service\Users\Interfaces\PutUsersServiceInterface;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Repository\Doctrine\TraderRepository;
use Wise\User\Repository\Doctrine\UserRepository;
use Wise\User\Tests\Support\AdminApiTester;

class DeleteUserCest
{

    /**
     * @var Trader[] $traders
     */
    private array $traders = [];

    /**
     * @var Client[] $traders
     */
    private array $clients = [];

    private ?UserRepository $userRepository = null;

    public function _before(AdminApiTester $I): void
    {
        $I->amBearerAuthenticated($I->takeAuthorizationCode());
        $this->prepareDataForTests($I);
    }

    /**
     * Czy można usunąć użytkownika
     * 1. Utworzenie Użytkownika
     * 2. Weryfikacja czy istnieje użytkownik
     * 3. Usunięcie użytkownika
     * 4. Weryfikacja czy został usunięty użytkownik
     */
    public function correctDeleteUser(AdminApiTester $I): void
    {
        $I->wantToTest('DELETE /api/admin/users - Możliwość usunięcia użytkownika');
        $user = $this->createUser($I);

        $I->assertTrue($this->userRepository->findOneBy(['idExternal' => $user->getIdExternal()]) instanceof User);

        $I->sendDelete('/users/'. $user->getIdExternal());
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);

        $I->assertTrue($this->userRepository->findOneBy(['idExternal' => $user->getIdExternal()]) === null);
    }


    private function prepareDataForTests(AdminApiTester $I): void
    {
        $traderRepository = $I->grabService(TraderRepository::class);
        $this->traders = $traderRepository->findAll();

        $clientRepository = $I->grabService(ClientRepository::class);
        $this->clients = $clientRepository->findAll();

        $this->userRepository = $I->grabService(UserRepository::class);
    }

    private function createUser(AdminApiTester $I): ?User
    {
        $object = [
            'id' => $I->generateId('USER'),
            'client_id' => $this->clients[0]->getIdExternal(),
            'role_internal_id' => UserRoleEnum::ROLE_USER->value,
            'trader_id' => $this->traders[0]->getIdExternal(),
            'login' => $I->generateId('USER'),
            'password' => $I->fake()->password(8),
            'first_name' => $I->fake()->firstName(),
            'last_name' => $I->fake()->lastName(),
            'email' => $I->fake()->email(),
            'phone' => $I->fake()->phoneNumber(),
            'is_active' => true,
        ];

        $PatchUserService = $I->grabService(PutUsersServiceInterface::class);
        $PatchUserService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutUsersDto::class);
        return $this->userRepository->findOneBy(['idExternal' => $object['id']]);
    }
}