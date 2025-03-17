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

class PutUserCest
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
     * Weryfikacja poprawnego dodania nowego użytkownika
     * 1. Dodanie nowego użytkownika
     * 2. Weryfikacja czy użytkownik został dodany za pomocą GET
     * 3. Weryfikacja czy użytkownik został dodany za pomocą repozytorium
     */
    public function correctPutUser(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Poprawnie dodanie nowego użytkownika. Weryfikacaj za pomoca repozytorium i repozytorium ');

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

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja Get
        $responseGet = $I->sendGetAsJson('/users', ['id' => $object['id']]);
        $I->seeResponseIsJson();
        $userResponseGet = $I->seeObjectWithElementsInResponse($responseGet, $object['id'], [
            'login' => strtolower($object['login']),
            'first_name' => $object['first_name'],
            'last_name' => $object['last_name'],
            'email' => $object['email'],
            'phone' => $object['phone'],
            'is_active' => $object['is_active'],
            'role_internal_id' => $object['role_internal_id'],
            'client_id' => $object['client_id'],
            'trader_internal_id' => $this->traders[0]->getId(),
        ]);
        $I->assertStringContainsString('$2', $userResponseGet['password']);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals(strtolower($object['login']), $user->getLogin());
        $I->assertEquals($object['first_name'], $user->getFirstName());
        $I->assertEquals($object['last_name'], $user->getLastName());
        $I->assertEquals($object['email'], $user->getEmail());
        $I->assertEquals($object['phone'], $user->getPhone());
        $I->assertEquals($object['is_active'], $user->getIsActive());
        $I->assertEquals($this->clients[0]->getId(), $user->getClientId());
        $I->assertEquals($this->traders[0]->getId(), $user->getTraderId());
        $I->assertEquals($object['role_internal_id'], $user->getRoleId());
        $I->assertStringContainsString('$2', $user->getPassword());
    }

    /**
     * Weryfikacja możliwości zmiany id zewnętrznego
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana id zewnętrznego
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyExternalId(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji id zewnętrznego');
        $user = $this->createUser($I);

        $object = [
            'internal_id' => $user->getId(),
            'id' => $I->generateId('USER')
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['id'], $user->getIdExternal());
    }

    /**
     * Weryfikacja możliwości zmiany roli
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana roli
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyRoles(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji roli');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'role_internal_id' => UserRoleEnum::ROLE_ADMIN->value,
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['role_internal_id'], $user->getRoleId());
    }

    /**
     * Weryfikacja możliwości zmiany sprzedawcy
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana sprzedawcy
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyTrader(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji sprzedawcy');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'trader_id' => $this->traders[1]->getIdExternal(),
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($this->traders[1]->getId(), $user->getTraderId());
    }

    /**
     * Weryfikacja możliwości zmiany imienia
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana imienia
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyFirstname(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji imienia');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'first_name' => $I->fake()->firstName(),
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['first_name'], $user->getFirstName());
    }

    /**
     * Weryfikacja możliwości zmiany nazwiska
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana nazwiska
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyLastname(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji nazwiska');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'last_name' => $I->fake()->lastName(),
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['last_name'], $user->getLastName());
    }

    /**
     * Weryfikacja możliwości zmiany emaila
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana emaila
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyEmail(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji emaila');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'email' => $I->fake()->email(),
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['email'], $user->getEmail());
    }

    /**
     * Weryfikacja możliwości zmiany numeru telefonu
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana numeru telefonu
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyPhoneNumber(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji numeru telefonu');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'phone' => $I->fake()->phoneNumber(),
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['phone'], $user->getPhone());
    }

    /**
     * Weryfikacja możliwości zmiany aktywności użytkownika
     * 1. Dodanie nowego użytkownika
     * 2. Zmiana aktywności użytkownika
     * 3. Weryfikacja czy użytkownik został poprawnie zmodyfikowany za pomocą repozytorium
     */
    public function correctPutUserCanModifyIsActive(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Możliwość modyfikacji aktywności użytkownika');
        $user = $this->createUser($I);

        $object = [
            'id' => $user->getIdExternal(),
            'is_active' => false,
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $userResponseGet = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);


        // Weryfikacja repozytorium
        $user = $this->userRepository->find(['id' => $userResponseGet['internal_id']]);
        $I->assertEquals($object['is_active'], $user->getIsActive());
    }

    /**
     * Dodanie użytkownika bez podania roleId (domyślnie da role id)
     * 1. Dodanie użytkownika
     * 2. Pobranie danych z repozytorium
     * 3. Weryfikacja roli
     * @param AdminApiTester $I
     * @return void
     */
    public function correctPutUserWithoutRoleId(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Poprawne przyznanie roli użytkownika bez podania roli w payload');
        $object = [
            'id' => $I->generateId('USER'),
            'client_id' => $this->clients[0]->getIdExternal(),
            'login' => $I->generateId('USER'),
            'password' => $I->fake()->password(8),
            'create_date' => '2023-10-26 14:46:59',
            'email' => 'info@example.com',
            'first_name' => 'Wiesław',
            'last_name' => 'Kowalski',
            'phone' => '1234456789',
            'is_active' => true,
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $putUser = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $user = $this->userRepository->find(['id' => $putUser['internal_id']]);

        // Weryfikacja roli
        $I->assertEquals(UserRoleEnum::ROLE_USER->value, $user->getRoleId());

        // Weryfikacja pozostałych danych
        $I->assertEquals(strtolower($object['login']), $user->getLogin());
        $I->assertEquals($object['first_name'], $user->getFirstName());
        $I->assertEquals($object['last_name'], $user->getLastName());
        $I->assertEquals($object['phone'], $user->getPhone());
        $I->assertEquals($object['email'], $user->getEmail());
    }

    /**
     * Dodanie użytkownika z roleInternalId (weryfikacja czy została zapisana poprawna rola)
     * 1. Dodanie użytkownika
     * 2. Pobranie danych z repozytorium
     * 3. Weryfikacja roli
     * @param AdminApiTester $I
     * @return void
     */
    public function correctPutUserWithRoleId(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Poprawne przyznanie roli użytkownika z roleInternalId');
        $object = [
            'id' => $I->generateId('USER'),
            'client_id' => $this->clients[0]->getIdExternal(),
            'login' => $I->generateId('USER'),
            'password' => $I->fake()->password(8),
            'role_internal_id' => 3,
            'create_date' => '2023-10-26 14:46:59',
            'email' => 'info@example.com',
            'first_name' => 'Wiesław',
            'last_name' => 'Kowalski',
            'phone' => '1234456789',
            'is_active' => true,
        ];

        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $putUser = $I->seeObjectWithElementsInResponse($responsePut, $object['id'], [
            'id' => $object['id'],
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        $user = $this->userRepository->find(['id' => $putUser['internal_id']]);

        // Weryfikacja roli
        $I->assertEquals($object['role_internal_id'], $user->getRoleId());

        // Weryfikacja pozostałych danych
        $I->assertEquals(strtolower($object['login']), $user->getLogin());
        $I->assertEquals($object['first_name'], $user->getFirstName());
        $I->assertEquals($object['last_name'], $user->getLastName());
        $I->assertEquals($object['phone'], $user->getPhone());
        $I->assertEquals($object['email'], $user->getEmail());
    }

    /**
     * Poprawne przyznanie podczas modyfikacji roli użytkownika z roleInternalId
     * 1. Dodanie użytkownika
     * 2. Modyfikacja użytkownika
     * 3. Pobranie danych z repozytorium
     * 4. Weryfikacja roli
     * @param AdminApiTester $I
     * @return void
     */
    public function correctPutUserModificationRoleId(AdminApiTester $I): void
    {
        $I->wantToTest('PUT /api/admin/users - Poprawne przyznanie podczas modyfikacji roli użytkownika z roleInternalId');
        $object = [
            'id' => $I->generateId('USER'),
            'client_id' => $this->clients[0]->getIdExternal(),
            'login' => $I->generateId('USER'),
            'password' => $I->fake()->password(8),
            'role_internal_id' => 3,
            'create_date' => '2023-10-26 14:46:59',
            'email' => 'info@example.com',
            'first_name' => 'Wiesław',
            'last_name' => 'Kowalski',
            'phone' => '1234456789',
            'is_active' => true,
        ];

        // Dodanie
        $responsePut = $I->sendPutAsJson('/users', [
            'objects' => [
                $object
            ]
        ]);


        // Modyfikacja
        $objectModification = [
            'id' => $object['id'],
            'role_internal_id' => 5,
        ];

        $I->sendPutAsJson('/users', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);

        $user = $this->userRepository->findOneBy(['idExternal' => $object['id']]);
        // Weryfikacja roli
        $I->assertEquals($objectModification['role_internal_id'], $user->getRoleId());

        // Weryfikacja pozostałych danych
        $I->assertEquals(strtolower($object['login']), $user->getLogin());
        $I->assertEquals($object['first_name'], $user->getFirstName());
        $I->assertEquals($object['last_name'], $user->getLastName());
        $I->assertEquals($object['phone'], $user->getPhone());
        $I->assertEquals($object['email'], $user->getEmail());
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
            'client_internal_id' => $this->clients[0]->getId(),
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

        $putUserService = $I->grabService(PutUsersServiceInterface::class);
        $putUserService->process([], json_encode([
            'objects' => [
                $object
            ]
        ]), PutUsersDto::class);
        return $this->userRepository->findOneBy(['idExternal' => $object['id']]);
    }
}
