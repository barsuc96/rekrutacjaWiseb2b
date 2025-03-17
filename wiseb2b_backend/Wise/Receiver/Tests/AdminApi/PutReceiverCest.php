<?php

namespace Wise\Receiver\Tests\AdminApi;

use Wise\Client\Domain\Client\ClientRepositoryInterface;
use Wise\Client\Repository\Doctrine\ClientRepository;
use Wise\Receiver\Tests\Support\AdminApiTester;

class PutReceiverCest
{

    private ClientRepositoryInterface $clientRepository;
    private array $clients = [];
    public function _before(AdminApiTester $I)
    {
        $I->amBearerAuthenticated($I->takeAuthorizationCode());
        $this->prepareDataForTest($I);
    }

    /**
     * Czy można dodać odbiorcę
     * 1. Utworzenie odbiorcy
     * 2. Weryfikacja czy istnieje odbiorca
     */
    public function correctPutReceiver(AdminApiTester $I){
        $I->wantToTest('PUT /api/admin/receivers - Poprawne dodanie odbiorcy');

        // Dodanie odbiorcy
        $object = [
            'id' => $I->generateId('RECEIVER'),
            'client_id' => $this->clients[0]->getIdExternal(),
            'name' => 'Jan Kowalski',
            'delivery_address' => [
                'name' => 'Jan Kowalski S.A',
                'street' => 'ul. Kowalska 1',
                'city' => 'Warszawa',
                'postal_code' => '12-345',
                'country_code' => 'PL',
                'house_number' => '55',
                'apartment_number' => '1',
                'state' => 'Lodzkie'
            ],
            'email' => 'kowalski@email.com',
            'phone' => '123456789',
            'is_default' => 'true',
            'first_name' => 'Agnieszka',
            'last_name' => 'Nowak',
            'type' => 'primary'
        ];

        $I->sendPutAsJson('receivers', [
            'objects' => [
                $object
            ]
        ]);

        // Weryfikacja danych
        $responseGet = $I->sendGetAsJson('/receivers', ['id' =>  $object['id']]);
        $I->assertSame($responseGet['objects'][0]['id'], $object['id']);
        $I->assertSame($responseGet['objects'][0]['client_id'], $object['client_id']);
        $I->assertSame($responseGet['objects'][0]['name'], $object['name']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['name'], $object['delivery_address']['name']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['street'], $object['delivery_address']['street']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['city'], $object['delivery_address']['city']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['postal_code'], $object['delivery_address']['postal_code']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['country_code'], $object['delivery_address']['country_code']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['house_number'], $object['delivery_address']['house_number']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['apartment_number'], $object['delivery_address']['apartment_number']);
        $I->assertSame($responseGet['objects'][0]['delivery_address']['state'], $object['delivery_address']['state']);
        $I->assertSame($responseGet['objects'][0]['email'], $object['email']);
        $I->assertSame($responseGet['objects'][0]['phone'], $object['phone']);
        $I->assertEquals($responseGet['objects'][0]['is_default'], $object['is_default']);
        $I->assertEquals($responseGet['objects'][0]['type'], $object['type']);
    }

    private function prepareDataForTest(AdminApiTester $I): void
    {
        $clientRepository = $I->grabService(ClientRepository::class);
        $this->clients = $clientRepository->findAll();
    }

}
