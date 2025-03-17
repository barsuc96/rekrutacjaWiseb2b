<?php

namespace Wise\Core\ApiUi\Dto\PanelManagement;

use OpenApi\Attributes as OA;

class ReplicationRequestRetryWithBodyDto
{
    #[OA\Property(
        description: 'Ciało requestu',
        example: '{"objects":[{"id":"200321","name":"TESTOWY KLIENT","email":"pawel.xxxxx@ffff.com","is_active":true,"default_currency":"PLN","tax_number":"00000000000","type":"COMPANY","phone":"48111222333","register_address":{"country_code":"PL","street":"UL. Testowa 17","house_number":"","apartment_number":"","city":"Testowo","postal_code":"11-222"}},{"id":"200322","name":"JAN TESTOWY","email":"tmp-200322@example.com","is_active":true,"default_currency":"PLN","type":"COMPANY","phone":"000000000","register_address":{"country_code":"PL","street":"Testowo 49","house_number":"","apartment_number":"","city":"Testów","postal_code":"11-222"}}]}',
    )]
    protected ?string $requestBody = null;

    #[OA\Property(
        description: 'Nagłówki requestu',
        example: '{"accept-encoding":["gzip, compress, deflate, br"],"user-agent":["axios\/1.7.4"],"x-request-uuid":["mh-test-n8n-1735710003790"],"authorization":["Bearer  eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJkZWE5ZDVlZmFhMGMzNGRkNjc0MDA4NzRmM2UxM2U3YSIsImp0aSI6IjU1ZDA3YmQ4NWExMmZhNzU2MmE4ZGRjZmNmNDUxNjU1MzZjMjJkM2RkZTA0MWE2NjJlOTY4OTEyMGY1Mzg5ZDY5ODA4MWQ1YmM5YzA2ZGFiIiwiaWF0IjoxNzM1NzA5NTQzLjIxMjAxMiwibmJmIjoxNzM1NzA5NTQzLjIxMjAxNCwiZXhwIjoxNzM1NzM4MzQzLjIwNjcxOSwic3ViIjoiIiwic2NvcGVzIjpbImFwaSJdfQ.fThsePiUuL-AprinYjeuVo3ZtzvRQGMuEaof1YbZoXf8mqZlCF_cs-e0mhjnyi6e0IcYWhNSy77prpP8974BkiGED-R2oUZcs6Xfkdv5yB0_RiZgVPwUkFM3hUN4flMqIXvEgwvxlibbS30ftb-dasUjHLH9IgiSYUIqHE1Mh3sUxky7-GzN0gDkG61u4UxOugYuOafc85oWwgTzC0CmDBji9Jn7DErC5-GYWdub7BiEy4ZEur4yFFWU1zzonTDPE6SeD8JIFpnbM-WbYY3n14YxNlf-oFefHakVaCsKoQ2EcK6hDJB0i83rtjrFhXEQ_eRL8cetYYF6OAtf_CHoKQ"],"content-type":["application\/json"],"accept":["application\/json"],"content-length":["7011"],"x-forwarded-proto":["https"],"x-forwarded-for":["10.162.0.130"],"x-real-ip":["10.162.0.130"],"connection":["upgrade"],"host":["test.agrotex.b2b.sente.pl"],"x-php-ob-level":["0"]}',
    )]
    protected ?string $requestHeaders = null;

    public function getRequestBody(): ?string
    {
        return $this->requestBody;
    }

    public function getRequestHeaders(): ?string
    {
        return $this->requestHeaders;
    }

    public function setRequestBody(?string $requestBody): void
    {
        $this->requestBody = $requestBody;
    }

    public function setRequestHeaders(?string $requestHeaders): void
    {
        $this->requestHeaders = $requestHeaders;
    }
}
