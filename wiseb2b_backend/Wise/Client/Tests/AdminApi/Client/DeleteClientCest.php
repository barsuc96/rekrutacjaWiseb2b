<?php

namespace Wise\Client\Tests\AdminApi\Client;

use Symfony\Component\HttpFoundation\HeaderBag;
use Wise\Client\ApiAdmin\Dto\Clients\PutClientsDto;
use Wise\Client\ApiAdmin\Service\Clients\PutClientsService;
use Wise\Client\Domain\Client\Client;
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

class DeleteClientCest
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

    public function _before(AdminApiTester $I)
    {
        $I->amBearerAuthenticated($I->takeAuthorizationCode());
        $this->prepareDataForTests($I);
    }


    /**
     * Poprawne usunięcie klienta
     * 1. Dodanie klienta
     * 2. Weryfikacja czy istnieje klient
     * 3. Usunięcie klienta
     * 4. Weryfikacja czy klient został usunięty
     */
    public function correctDeleteClient(AdminApiTester $I){
        $I->wantToTest('PATCH /api/admin/Client - Poprawne usunięcie klienta');

        $client = $this->createClient($I);

        // Weryfikacja czy istnieje klient
        $I->assertTrue($this->clientRepository->findOneBy(['id' => $client->getId()]) instanceof Client);

        // Usunięcie klienta
        $I->sendDelete('/clients/' . $client->getIdExternal());

        // Weryfikacja czy klient został usunięty
        $I->assertTrue($this->clientRepository->findOneBy(['id' => $client->getId()]) === null);
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
                'owner_name' => $I->fake()->name(),
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
