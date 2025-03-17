<?php

namespace Wise\Client\Tests\AdminApi\Client;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpFoundation\HeaderBag;
use Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto;
use Wise\Client\ApiAdmin\Service\Clients\PutClientsService;
use Wise\Client\Domain\Client\Client;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
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

class GetClientCest
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
    private ?ClientPaymentMethodRepositoryInterface $clientPaymentMethodRepository = null;

    public function _before(AdminApiTester $I)
    {
        $I->amBearerAuthenticated($I->takeAuthorizationCode());
        $this->prepareDataForTests($I);
    }

    /**
     * Wywołanie endpointu GET klient bez argumentów i sprawdzenie czy nie wyrzuca błędu.
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetWithoutParameters(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić klientów nie podając parametrów');
        $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        // Sprawdzenie czy zwrócona rekord z poprawnymi kolumnami
        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);
    }

    /**
     * Czy można zwrócić klienta który posiada konkretna metode platnosci?
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET - używając defaultPaymentMethodId
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUsingDefaultPaymentMethodId(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić klienta który posiada konkretna metode platnosci (defaultPaymentMethodId)');
        $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients', [
            'defaultPaymentMethodId' => $this->paymentMethods[0]->getIdExternal()
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);
    }

    /**
     * Czy można zwrócić klienta który posiada konkretna metode dostawy?
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET - używając defaultDeliveryMethodId
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUsingDefaultDeliveryMethodId(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić klienta który posiada konkretna metode dostawy (defaultDeliveryMethodId)');
        $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients', [
            'defaultDeliveryMethodId' => $this->deliveryMethods[0]->getIdExternal()
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);
    }


    /**
     * Czy można zwrócić klienta wraz metodami płatności?
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET - używając fetchPayments
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     * 5. Weryfikacja czy zwraca kolumne payments, zawiera jakieś rekordy oraz zwraca odpowiednie kolumny
     */
    public function correctGetUsingFetchPayments(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić klienta wraz metodami płatności (fetchPayments)');
        $client = $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients', [
            'fetchPayments' => 'true',
            'id' => $client->getIdExternal()
        ]);

        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);

        // Weryfikacja metod płatności

        $I->assertArrayHasKey('payments', $responseGET['objects'][0]);
        $I->assertGreaterThan(0, count($responseGET['objects'][0]['payments']));
        $I->assertArrayHasKey('client_id', $responseGET['objects'][0]['payments'][0]);
        $I->assertArrayHasKey('payment_method_id', $responseGET['objects'][0]['payments'][0]);
        $I->assertNotNull($responseGET['objects'][0]['payments'][0]['payment_method_id']);
        $I->assertNotNull($responseGET['objects'][0]['payments'][0]['client_id']);
    }

    /**
     * Czy można zwrócić klienta wraz metodami dostawy?
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET - używając fetchDeliveries
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     * 5. Weryfikacja czy zwraca kolumne deliveries, zawiera jakieś rekordy oraz zwraca odpowiednie kolumny
     */
    public function correctGetUsingFetchDeliveries(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić klienta wraz metodami dostawy (fetchDeliveries)');
        $client = $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients', [
            'fetchDeliveries' => 'true',
            'id' => $client->getIdExternal()
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);

        // Weryfikacja metod dostawy
        $I->assertArrayHasKey('deliveries', $responseGET['objects'][0]);
        $I->assertGreaterThan(0, count($responseGET['objects'][0]['deliveries']));
        $I->assertArrayHasKey('client_id', $responseGET['objects'][0]['deliveries'][0]);
        $I->assertArrayHasKey('delivery_method_id', $responseGET['objects'][0]['deliveries'][0]);
        $I->assertNotNull($responseGET['objects'][0]['deliveries'][0]['delivery_method_id']);
        $I->assertNotNull($responseGET['objects'][0]['deliveries'][0]['client_id']);
    }

    /**
     * Czy można zwrócić klientów, którzy są aktywni?
     * 1. Utworzenie Klienta
     * 2. Pobranie klientów za pomocą GET - używając isActive
     * 3. Weryfikacja czy istnieje więcej niz 0 elementów w objects
     * 4. Weryfikacja czy pierwszy element posiada wszystkie informacje (klucze)
     */
    public function correctGetUsingIsActive(AdminApiTester $I){
        $I->wantToTest('GET /api/admin/client - Można zwrócić wszysttkich aktywnych klientów (isActive)');
        $this->createClient($I);
        $responseGET = $I->sendGetAsJson('/clients', [
            'isActive' => true
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseContainsJson(['status' => 1, 'message' => 'SUCCESS']);
        $I->assertGreaterThan(0, $responseGET['count']);
        $I->assertEquals($responseGET['count'], count($responseGET['objects']));

        $I->assertArrayHasKey('name', $responseGET['objects'][0]);
        $I->assertArrayHasKey('email', $responseGET['objects'][0]);
        $I->assertArrayHasKey('phone', $responseGET['objects'][0]);
        $I->assertArrayHasKey('is_active', $responseGET['objects'][0]);
        $I->assertArrayHasKey('flags', $responseGET['objects'][0]);
        $I->assertArrayHasKey('tax_number', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_total', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trade_credit_free', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_currency', $responseGET['objects'][0]);
        $I->assertArrayHasKey('type', $responseGET['objects'][0]);
        $I->assertArrayHasKey('dropshipping_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('order_return_cost', $responseGET['objects'][0]);
        $I->assertArrayHasKey('free_delivery_limit', $responseGET['objects'][0]);
        $I->assertArrayHasKey('discount', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('client_parent_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_payment_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('default_delivery_method_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('trader_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('pricelist_internal_id', $responseGET['objects'][0]);
        $I->assertArrayHasKey('register_address', $responseGET['objects'][0]);
        $I->assertArrayHasKey('name', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('street', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('house_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('apartment_number', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('city', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('postal_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('country_code', $responseGET['objects'][0]['register_address']);
        $I->assertArrayHasKey('state', $responseGET['objects'][0]['register_address']);
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
        $this->clientPaymentMethodRepository = $I->grabService(ClientPaymentMethodRepositoryInterface::class);
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
                'country_code' => 'pl',
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
                'owner_name' => 'asdasdas' . rand(1,9999999999),
                'account' => '57106001938797265026589024',
                'bank_country_id' => 'PL',
                'bank_name' => 'mBANK',
                'bank_address' => 'ul. Sokolska 34, 40-086 Katowice',
            ],
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
