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

class GetUserCest
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
     * Wywołanie endpointu GET users bez argumentów i sprawdzenie czy nie wyrzuca błędu.
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserWithoutParameter(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika nie podając parametrów');
        $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);
    }

    /**
     * Pobrania użytkownika za pomocą idExternal
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserUsingIdExternal(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika za pomocą id zewnętrznego');
        $user = $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users', ['id' => $user->getIdExternal()]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);

        $I->seeObjectWithElementsInResponse($responseGET, $user->getIdExternal(), [
            'login' => $user->getLogin(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'is_active' => $user->getIsActive(),
            'role_internal_id' => $user->getRoleId()
        ]);
    }

    /**
     * Pobrania użytkownika za pomocą idInternal
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserUsingIdInternal(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika za pomocą id wewnętrznego');
        $user = $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users', ['internalId' => $user->getId()]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);

        $I->seeObjectWithElementsInResponse($responseGET, $user->getIdExternal(), [
            'login' => $user->getLogin(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'phone' => $user->getPhone(),
            'is_active' => $user->getIsActive(),
            'role_internal_id' => $user->getRoleId()
        ]);
    }

    /**
     * Pobrania użytkownika za pomocą clientId
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserUsingClientId(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika za pomocą clientId');
        $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users', ['clientId' => $this->clients[0]->getIdExternal()]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);
    }

    /**
     * Pobrania użytkownika za pomocą clientInternalId
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserUsingClientInternalId(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika za pomocą clientInternalId');
        $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users', ['clientInternalId' => $this->clients[0]->getId()]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);
    }

    /**
     * Pobrania użytkownika za pomocą roleId
     * 1. Utworzenie Użytkownika
     * 2. Pobranie użytkowników za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUserUsingRoleId(AdminApiTester $I): void
    {
        $I->wantToTest('GET /api/admin/users - Możliwość pobrania użytkownika za pomocą roleId');
        $user = $this->createUser($I);

        $responseGET = $I->sendGetAsJson('/users', ['roleId' => $user->getRoleId()]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $userObject = $responseGET['objects'][0];
        $I->assertArrayHasKey('login', $userObject);
        $I->assertArrayHasKey('password', $userObject);
        $I->assertArrayHasKey('email', $userObject);
        $I->assertArrayHasKey('phone', $userObject);
        $I->assertArrayHasKey('is_active', $userObject);
        $I->assertArrayHasKey('id', $userObject);
        $I->assertArrayHasKey('internal_id', $userObject);
        $I->assertArrayHasKey('client_id', $userObject);
        $I->assertArrayHasKey('client_internal_id', $userObject);
        $I->assertArrayHasKey('trader_internal_id', $userObject);
        $I->assertArrayHasKey('role_internal_id', $userObject);
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