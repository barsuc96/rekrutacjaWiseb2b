<?php


namespace Wise\Client\Tests\AdminApi\Client;

use Codeception\Attribute\Skip;
use Codeception\Util\HttpCode;
use Symfony\Component\HttpFoundation\HeaderBag;
use Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto;
use Wise\Client\ApiAdmin\Service\Clients\PutClientsService;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethod;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethod;
use Wise\Client\Repository\Doctrine\ClientDeliveryMethodRepository;
use Wise\Client\Repository\Doctrine\ClientPaymentMethodRepository;
use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Client\Tests\Support\AdminApiTester;
use Wise\Core\ApiAdmin\Dto\RequestDataDto\PutRequestDataDto;
use Wise\Delivery\Domain\DeliveryMethod\DeliveryMethod;
use Wise\Delivery\Repository\Doctrine\DeliveryMethodRepository;
use Wise\Payment\Domain\PaymentMethod\PaymentMethod;
use Wise\Payment\Repository\Doctrine\PaymentMethodRepository;
use Wise\Pricing\Domain\PriceList\PriceList;
use Wise\Pricing\Repository\Doctrine\PriceListRepository;
use Wise\User\Domain\Trader\Trader;
use Wise\User\Repository\Doctrine\TraderRepository;

class PutClientCest
{

    /**
     * @var PaymentMethod[] $paymentMethods
     */
    private array $paymentMethods = [];

    /**
     * @var DeliveryMethod[] $deliveryMethods
     */
    private array $deliveryMethods = [];

    /**
     * @var Trader[] $traders
     */
    private array $traders = [];

    /**
     * @var PriceList[] $priceLists
     */
    private array $priceLists = [];

    private ?ClientRepository $clientRepository = null;
    private ?ClientPaymentMethodRepository $clientPaymentMethodRepository = null;
    private ?ClientDeliveryMethodRepository $clientDeliveryMethodRepository = null;

    private ?PaymentMethodRepository $paymentMethodRepository = null;

    private ?DeliveryMethodRepository $deliveryMethodRepository = null;

    public function _before(AdminApiTester $I)
    {
        $I->amBearerAuthenticated($I->takeAuthorizationCode());
        $this->prepareDataForTests($I);
    }

    /**
     * Weryfikacja możliwości dodania Clienta ze wszystkimi poprawnymi danymi
     * 1. Dodanie klienta z poprawnymi danymi przez PUT
     * 2. Weryfikacja czy klienta został poprawnie dodany przez GET oraz Repository
     */
    public function correctPutClientf(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawnie dodanie nowego klienta. Weryfikacaj za pomoca bazy danych i repozytorium ');

        //PUT
        $clientId = $I->generateId('CLIENT');
        $object = $this->getClientObjectArray($clientId, $I);
        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $object
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $clientId, [
            'id' => $clientId,
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        /**
         * Weryfikacja
         */

        //GET
        $responseGet = $I->sendGetAsJson('/clients', ['id' => $clientId, 'fetchPayments' => true, 'fetchDeliveries' => true]);
        $clientResponseGet = $I->seeObjectWithElementsInResponse($responseGet, $clientId, [
            'name' => $object['name'],
            'email' => $object['email'],
            'phone' => $object['phone'],
            'tax_number' => $object['tax_number'],
            'default_currency' => $object['default_currency'],
            'type' => $object['type'],
            'dropshipping_cost' => $object['dropshipping_cost'],
            'order_return_cost' => $object['order_return_cost'],
            'free_delivery_limit' => $object['free_delivery_limit'],
            'discount' => $object['discount'],
            'trader_id' => $object['trader_id'],
            'pricelist_id' => $object['pricelist_id'],
            'flags' => $object['flags'],
            'status' => 0,
        ]);

        $I->assertEquals($object['register_address']['name'], $clientResponseGet['register_address']['name']);
        $I->assertEquals($object['register_address']['street'], $clientResponseGet['register_address']['street']);
        $I->assertEquals($object['register_address']['house_number'], $clientResponseGet['register_address']['house_number']);
        $I->assertEquals($object['register_address']['apartment_number'], $clientResponseGet['register_address']['apartment_number']);
        $I->assertEquals($object['register_address']['city'], $clientResponseGet['register_address']['city']);
        $I->assertEquals($object['register_address']['postal_code'], $clientResponseGet['register_address']['postal_code']);
        $I->assertEquals($object['register_address']['country_code'], $clientResponseGet['register_address']['country_code']);
        $I->assertEquals($object['register_address']['state'], $clientResponseGet['register_address']['state']);
        $I->assertEquals($object['return_bank_account']['owner_name'], $clientResponseGet['return_bank_account']['owner_name']);
        $I->assertEquals($object['return_bank_account']['account'], $clientResponseGet['return_bank_account']['account']);
        $I->assertEquals($object['return_bank_account']['bank_address'], $clientResponseGet['return_bank_account']['bank_address']);
        $I->assertEquals($object['return_bank_account']['bank_name'], $clientResponseGet['return_bank_account']['bank_name']);
        $I->assertEquals($object['return_bank_account']['bank_name'], $clientResponseGet['return_bank_account']['bank_name']);

        $I->assertEqualsAtLeastOneElementFromArray($clientResponseGet['deliveries'][0]['delivery_method_id'],[
            $object['deliveries'][0]['delivery_method_id'],
            $object['deliveries'][1]['delivery_method_id']
        ]);
        $I->assertEqualsAtLeastOneElementFromArray($clientResponseGet['deliveries'][1]['delivery_method_id'],[
            $object['deliveries'][0]['delivery_method_id'],
            $object['deliveries'][1]['delivery_method_id']
        ]);

        $I->assertEqualsAtLeastOneElementFromArray($clientResponseGet['payments'][0]['payment_method_id'],[
            $object['payments'][0]['payment_method_id'],
            $object['payments'][1]['payment_method_id']
        ]);
        $I->assertEqualsAtLeastOneElementFromArray($clientResponseGet['payments'][1]['payment_method_id'],[
            $object['payments'][0]['payment_method_id'],
            $object['payments'][1]['payment_method_id']
        ]);


        //Weryfikacja zapisanych danych za pomoca encji z repozytorium
        $client = $this->clientRepository->findOneBy(['idExternal' => $clientId]);
        $defaultPaymentMethod = $this->paymentMethodRepository->findOneBy(['idExternal' => $object['default_payment_method_id']]);
        $defaultDeliveryMethod = $this->deliveryMethodRepository->findOneBy(['idExternal' => $object['default_delivery_method_id']]);
        $I->assertNotNull($client);
        $I->assertEquals($object['name'], $client->getName());
        $I->assertEquals($object['email'], $client->getEmail());
        $I->assertEquals($object['phone'], $client->getPhone());
        $I->assertEquals($object['tax_number'], $client->getTaxNumber());
        $I->assertEquals($object['register_address']['name'], $client->getRegisterAddress()->getName());
        $I->assertEquals($object['register_address']['street'], $client->getRegisterAddress()->getStreet());
        $I->assertEquals($object['register_address']['house_number'], $client->getRegisterAddress()->getHouseNumber());
        $I->assertEquals($object['register_address']['apartment_number'], $client->getRegisterAddress()->getApartmentNumber());
        $I->assertEquals($object['register_address']['city'], $client->getRegisterAddress()->getCity());
        $I->assertEquals($object['register_address']['postal_code'], $client->getRegisterAddress()->getPostalCode());
        $I->assertEquals($object['register_address']['country_code'], $client->getRegisterAddress()->getCountryCode());
        $I->assertEquals($object['register_address']['state'], $client->getRegisterAddress()->getState());
        $I->assertEquals($object['client_parent_id'], $client->getClientParentId());
        $I->assertEquals($defaultPaymentMethod->getId(), $client->getDefaultPaymentMethodId());
        $I->assertEquals($defaultDeliveryMethod->getId(), $client->getDefaultDeliveryMethodId());
        $I->assertEquals($object['flags'], $client->getFlags());
        $I->assertEquals($object['return_bank_account']['owner_name'], $client->getReturnBankAccount()->getOwnerName());
        $I->assertEquals($object['return_bank_account']['account'], $client->getReturnBankAccount()->getAccount());
        $I->assertEquals($object['return_bank_account']['bank_name'], $client->getReturnBankAccount()->getBankName());
        $I->assertEquals($object['return_bank_account']['bank_address'], $client->getReturnBankAccount()->getBankAddress());
        $I->assertEquals($object['trade_credit_total'], $client->getTradeCreditTotal());
        $I->assertEquals($object['trade_credit_free'], $client->getTradeCreditFree());
        $I->assertEquals($object['default_currency'], $client->getDefaultCurrency());
        $I->assertEquals($object['type'], $client->getType());
        $I->assertEquals($object['dropshipping_cost'], $client->getDropshippingCost());
        $I->assertEquals($object['order_return_cost'], $client->getOrderReturnCost());
        $I->assertEquals($object['free_delivery_limit'], $client->getFreeDeliveryLimit());
        $I->assertEquals($object['discount'], $client->getDiscount());
        $I->assertEquals($object['trader_id'], $client->getTraderId());
        $I->assertEquals($this->priceLists[0]->getId(), $client->getPricelistId());
        $I->assertTrue($this->clientPaymentMethodRepository->findOneBy(['clientId' => $client, 'paymentMethodId' => $this->paymentMethods[0]->getId()]) instanceof ClientPaymentMethod);
        $I->assertTrue($this->clientPaymentMethodRepository->findOneBy(['clientId' => $client, 'paymentMethodId' => $this->paymentMethods[1]->getId()]) instanceof ClientPaymentMethod);
        $I->assertTrue($this->clientDeliveryMethodRepository->findOneBy(['clientId' => $client, 'deliveryMethodId' => $this->deliveryMethods[0]->getId()]) instanceof ClientDeliveryMethod);
        $I->assertTrue($this->clientDeliveryMethodRepository->findOneBy(['clientId' => $client, 'deliveryMethodId' => $this->deliveryMethods[1]->getId()]) instanceof ClientDeliveryMethod);
    }

    /**
     * Weryfikacja możliwości zmiany adresu
     * 1. Dodanie klienta
     * 2. Zmiana adresu
     * 3. Pobranie klienta i weryfikacja adresu
     */
    #[Skip('WIS-2173')]
    public function correctPutClientModificationAddress(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja adresu klienta');

        // Dodanie klienta

        $client = $this->createClient($I);

        // Modyfikacja adresu

        $objectModification = [
            'internal_id' => $client->getId(),
            'register_address' => [
                'name' => $I->fake()->name(),
                'street' => $I->fake()->streetAddress(),
                'house_number' => $I->fake()->buildingNumber(),
                'apartment_number' => $I->fake()->buildingNumber(),
                'city' => $I->fake()->city(),
                'postal_code' => $I->fake()->postcode(),
                'country_code' => 'PL',
                'state' => $I->fake()->sentence(2),
                'is_active' => $I->fake()->boolean(100),
            ]
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja zmiany adresu

        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $I->assertEquals($objectModification['register_address']['name'], $client->getRegisterAddress()->getName());
        $I->assertEquals($objectModification['register_address']['street'], $client->getRegisterAddress()->getStreet());
        $I->assertEquals($objectModification['register_address']['house_number'], $client->getRegisterAddress()->getHouseNumber());
        $I->assertEquals($objectModification['register_address']['apartment_number'], $client->getRegisterAddress()->getApartmentNumber());
        $I->assertEquals($objectModification['register_address']['city'], $client->getRegisterAddress()->getCity());
        $I->assertEquals($objectModification['register_address']['postal_code'], $client->getRegisterAddress()->getPostalCode());
        $I->assertEquals($objectModification['register_address']['country_code'], $client->getRegisterAddress()->getCountryCode());
        $I->assertEquals($objectModification['register_address']['state'], $client->getRegisterAddress()->getState());
    }

    /**
     * Weryfikacja możliwości zmiany numeru telefony
     * 1. Dodanie klienta
     * 2. Zmiana numeru telefonu
     * 3. Pobranie klienta i weryfikacja numeru telefonu
     */
    public function correctPutClientModificationPhoneNumber(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja numeru telefonu');

        // Dodanie klienta

        $client = $this->createClient($I);

        // Modyfikacja numeru telefonu

        $objectModification = [
            'internal_id' => $client->getId(),
            'phone' => (string)$I->fake()->randomNumber(9, true),
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja zmiany numeru telefonu

        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $I->assertEquals($objectModification['phone'], $client->getPhone());
    }

    /**
     * Weryfikacja możliwości zmiany adresu email
     * 1. Dodanie klienta
     * 2. Zmiana adresu email
     * 3. Pobranie klienta i weryfikacja adresu email
     */
    public function correctPutClientModificationEmail(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja adresu email');

        // Dodanie klienta

        $client = $this->createClient($I);

        // Modyfikacja adresu email

        $objectModification = [
            'internal_id' => $client->getId(),
            'email' => $I->fake()->email(),
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja zmiany adresu email

        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $I->assertEquals($objectModification['email'], $client->getEmail());
    }

    /**
     * Weryfikacja możliwości zmiany konta bankowego
     * 1. Dodanie klienta
     * 2. Zmiana konta bankowego
     * 3. Pobranie klienta i weryfikacja konta bankowego (bez zmiany nazwy właściciela)
     */
    #[Skip('WIS-2173')]
    public function correctPutClientModificationAccountBank(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja konta bankowego');

        // Dodanie klienta

        $client = $this->createClient($I);
        $lastOwnerName = $client->getReturnBankAccount()->getOwnerName();

        // Modyfikacja konta bankowego

        $objectModification = [
            'internal_id' => $client->getId(),
            'return_bank_account' => [
                'account' => '9999600193879726502659999',
                'bank_country_id' => 'PL',
                'bank_name' => 'ING Bank Śląski S.A.',
                'bank_address' => 'ul. Mrongowska 12, 54-321 Warszawa',
            ],
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja zmiany konta bankowego
        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $I->assertEquals($lastOwnerName, $client->getReturnBankAccount()->getOwnerName());
        $I->assertEquals($objectModification['return_bank_account']['account'], $client->getReturnBankAccount()->getAccount());
        $I->assertEquals($objectModification['return_bank_account']['bank_name'], $client->getReturnBankAccount()->getBankName());
        $I->assertEquals($objectModification['return_bank_account']['bank_address'], $client->getReturnBankAccount()->getBankAddress());
    }


    /**
     * Weryfikacja możliwości zmiany domyślnej metody płatności
     * 1. Dodanie klienta
     * 2. Zmiana domyślnej metody płatności
     * 3. Pobranie klienta i weryfikacja domyślnej metody płatności
     */
    public function correctPutClientModificationDefaultPaymentMethod(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja domyślnej metody płatności');

        // Dodanie klienta

        $client = $this->createClient($I);

        // Modyfikacja

        $objectModification = [
            'internal_id' => $client->getId(),
            'default_payment_method_id' => $this->paymentMethods[3]?->getIdExternal(),
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja
        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $paymentMethod = $this->paymentMethodRepository->findOneBy(['id' => $client->getDefaultPaymentMethodId()]);
        $I->assertEquals($objectModification['default_payment_method_id'], $paymentMethod->getIdExternal());
    }

    /**
     * Weryfikacja możliwości zmiany domyślnej metody dostawy
     * 1. Dodanie klienta
     * 2. Zmiana domyślnej metody dostawy
     * 3. Pobranie klienta i weryfikacja domyślnej metody dostawy
     */
    public function correctPutClientModificationDefaultDeliveryMethod(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawna modyfikacja domyślnej metody dostawy');

        // Dodanie klienta

        $client = $this->createClient($I);

        // Modyfikacja

        $objectModification = [
            'internal_id' => $client->getId(),
            'default_delivery_method_id' => $this->deliveryMethods[3]?->getIdExternal(),
        ];

        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja
        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
        $deliveryMethod = $this->deliveryMethodRepository->findOneBy(['id' => $client->getDefaultDeliveryMethodId()]);
        $I->assertEquals($objectModification['default_delivery_method_id'], $deliveryMethod->getIdExternal());
    }


    /**
     * Weryfikacja możliwości nadpisania metod płatności przez PUT
     * 1. Dodanie klienta
     * 2. Zmiana domyślnej metody dostawy
     * 3. Pobranie klienta i weryfikacja domyślnej metody dostawy
     */
    #[Skip('Oczekujemy na odpowiedź Wojtka => Wojciech Struski - zagadnienia do omówienia')]
    public function correctPutClientModificationOverwritingPayments(AdminApiTester $I)
    {
        $I->wantToTest('PUT /api/admin/Client - Poprawne nadpisanie metod płatności');

        // Dodanie klienta

        /** @var Client $client */
        $client = $this->createClient($I);
        // Modyfikacja

        /**
         * Problem jest taki, że w pliku PutClientService.php w metodzie prepareAndPutClientPaymentMethod (linia 108)
         * usuwa element a później sprawdza czy on istnieje
         */
        $currentPaymentMethodsForClient = $this->clientPaymentMethodRepository->findBy(['clientId' => $client->getId()]);
        $objectModification = [
            'id' => $client->getIdExternal(),
            'payments' => [
                [
                    'internal_id' => $currentPaymentMethodsForClient[0]->getId(),
                ],
                [
                    'payment_method_id' => $this->paymentMethods[2]->getIdExternal()
                ]
            ],
        ];
        $responsePut = $I->sendPutAsJson('/clients', [
            'objects' => [
                $objectModification
            ]
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->seeObjectWithElementsInResponse($responsePut, $client->getIdExternal(), [
            'id' => $client->getIdExternal(),
            'message' => 'SUCCESS',
            'status' => 1
        ]);

        // Weryfikacja
        $client = $this->clientRepository->findOneBy(['idExternal' => $client->getIdExternal()]);
    }

    private function prepareDataForTests(AdminApiTester $I): void
    {
        $paymentMethodRepository = $I->grabService(PaymentMethodRepository::class);
        $this->paymentMethods = $paymentMethodRepository->findAll();

        $deliveryMethodRepository = $I->grabService(DeliveryMethodRepository::class);
        $this->deliveryMethods = $deliveryMethodRepository->findAll();

        $traderRepository = $I->grabService(TraderRepository::class);
        $this->traders = $traderRepository->findAll();

        $priceListRepository = $I->grabService(PriceListRepository::class);
        $this->priceLists = $priceListRepository->findAll();

        $this->clientRepository = $I->grabService(ClientRepository::class);
        $this->clientPaymentMethodRepository = $I->grabService(ClientPaymentMethodRepository::class);
        $this->clientDeliveryMethodRepository = $I->grabService(ClientDeliveryMethodRepository::class);
        $this->paymentMethodRepository = $I->grabService(PaymentMethodRepository::class);
        $this->deliveryMethodRepository = $I->grabService(DeliveryMethodRepository::class);
    }

    private function getClientObjectArray(string $clientId, AdminApiTester $I): array
    {
        return [
            'id' => $clientId,
            'name' => $I->fake()->name(),
            'register_address' => [
                'name' => 'Agnieszka Wegorz',
                'street' => 'Kwiatowa',
                'house_number' => '34',
                'apartment_number' => '18',
                'city' => 'Testowo',
                'postal_code' => '00-000',
                'country_code' => 'PL',
                'state' => $I->fake()->sentence(2),
            ],
            'email' => $I->fake()->email(),
            'phone' => (string)$I->fake()->randomNumber(9, true),
            'tax_number' => 'PL' . rand(111111111, 999999999),
            'client_parent_id' => null,
            'default_payment_method_id' => $this->paymentMethods[0]->getIdExternal(),
            'default_delivery_method_id' => $this->deliveryMethods[0]->getIdExternal(),
            'flags' => 'FLAG',
            'return_bank_account' => [
                'owner_name' => $I->fake()->name(),
                'account' => '57106001938797265026589024',
                'bank_country_id' => 'PL',
                'bank_name' => 'mBANK',
                'bank_address' => 'ul. Sokolska 34, 40-086 Katowice',
            ],
            'trade_credit_total' => 43.54,
            'trade_credit_free' => 12.64,
            'default_currency' => 'PLN',
            'type' => 'CANDIDATE',
            'dropshipping_cost' => 4.54,
            'order_return_cost' => 40.34,
            'free_delivery_limit' => 156.42,
            'discount' => 6.43,
            'trader_id' => $this->traders[0]->getIdExternal(),
            'pricelist_id' => $this->priceLists[0]->getIdExternal(),
            'payments' => [
                [
                    'payment_method_id' => $this->paymentMethods[0]->getIdExternal()
                ],
                [
                    'payment_method_id' => $this->paymentMethods[1]->getIdExternal()
                ]
            ],
            'deliveries' => [
                [
                    'delivery_method_id' => $this->deliveryMethods[0]->getIdExternal()
                ],
                [
                    'delivery_method_id' => $this->deliveryMethods[1]->getIdExternal()
                ]
            ]
        ];
    }

    private function createClient(AdminApiTester $I): Client
    {
        $putClientService = $I->grabService(PutClientsService::class);
        $clientExternalId = $I->generateId('CLIENT');
        $object = $this->getClientObjectArray($clientExternalId, $I);
        $requestContent = json_encode([
            'objects' => [
                $object
            ]
        ]);

        $requestDto = new PutRequestDataDto();
        $requestDto
            ->setClearRequestContent($requestContent)
            ->setRequestContent($requestContent)
            ->setIsPatch(false)
            ->setRequestDtoClass(PutClientsDto::class)
            ->setHeaders(new HeaderBag());

        $putClientService->process($requestDto);

        return $this->clientRepository->findOneBy(['idExternal' => $clientExternalId]);
    }

}
