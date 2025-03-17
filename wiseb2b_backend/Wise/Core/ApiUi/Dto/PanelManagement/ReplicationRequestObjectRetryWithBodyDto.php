<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;

class ReplicationRequestObjectRetryWithBodyDto
{
    #[OA\Property(
        description: 'Ciało requestu',
        example: '{"objects":[{"id":"200321","name":"TESTOWY KLIENT","email":"pawel.xxxxx@ffff.com","is_active":true,"default_currency":"PLN","tax_number":"00000000000","type":"COMPANY","phone":"48111222333","register_address":{"country_code":"PL","street":"UL. Testowa 17","house_number":"","apartment_number":"","city":"Testowo","postal_code":"11-222"}},{"id":"200322","name":"JAN TESTOWY","email":"tmp-200322@example.com","is_active":true,"default_currency":"PLN","type":"COMPANY","phone":"000000000","register_address":{"country_code":"PL","street":"Testowo 49","house_number":"","apartment_number":"","city":"Testów","postal_code":"11-222"}}]}',
    )]
    protected ?string $requestBody = null;

    public function getRequestBody(): ?string
    {
        return $this->requestBody;
    }

    public function setRequestBody(?string $requestBody): void
    {
        $this->requestBody = $requestBody;
    }
}
