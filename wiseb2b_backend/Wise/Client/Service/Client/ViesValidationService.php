<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client;

use Wise\Client\Service\Client\Exceptions\ViesValidationException;
use Wise\Client\Service\Client\Interfaces\ViesValidationServiceInterface;
use Wise\Core\Exception\CommonLogicException;

/**
 * Serwis do weryfikacji numeru VAT UE
 */
class ViesValidationService implements ViesValidationServiceInterface
{
    private \SoapClient $client;

    public function __construct()
    {
        if (!class_exists('SoapClient')) {
            throw new CommonLogicException('API_ERROR_SOAP_INSTALLED');
        }

        $this->client = new \SoapClient(self::WSDL_URL, $this->getWsdlOptions());
    }

    protected const WSDL_URL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    public function __invoke(ViesValidationServiceParams $params): bool
    {
        try{
            return $this->verifyViesByWSDL($params->getTaxNumber());
        } catch (\Exception $e) {
            throw new ViesValidationException(previous: $e);
        }
    }

    protected function verifyViesByWSDL(string $taxNumber): bool
    {
        $taxNumber = $this->prepareTaxNumer($taxNumber);

        if($taxNumber === false){
            return false;
        }

        return $this->client->checkVat([
            'countryCode' => strtoupper($taxNumber['countryCode']),
            'vatNumber' => $taxNumber['vatNumber']
        ])->valid;
    }

    protected function prepareTaxNumer(string $taxNumber): array|bool
    {
        $taxNumber = preg_replace('/[^a-z0-9]+/i', '', $taxNumber);

        if (strlen($taxNumber) <= 2) {
            return false;
        }

        return [
            'countryCode' => substr($taxNumber, 0, 2),
            'vatNumber' => substr($taxNumber, 2)
        ];
    }

    /**
     * Zwraca ustawienia WSDL dla klienta SOAP
     * @return array
     */
    protected function getWsdlOptions(): array
    {
        return [
            'debug' => false,
            'trace' => true,
            'connection_timeout' => 300,
            'wsdl_cache' => WSDL_CACHE_BOTH,
            'exceptions' => 1
        ];
    }
}
