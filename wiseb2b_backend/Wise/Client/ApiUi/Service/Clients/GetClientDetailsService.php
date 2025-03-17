<?php

declare(strict_types=1);

namespace Wise\Client\ApiUi\Service\Clients;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Client\ApiUi\Service\Clients\Interfaces\GetClientsDetailsServiceInterface;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractGetDetailsUiApiService;

class GetClientDetailsService extends AbstractGetDetailsUiApiService implements GetClientsDetailsServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($sharedActionService, $getClientDetailsService);
    }

    /**
     * Metoda pozwala przekształcić serviceDto przed transformacją do responseDto
     * @param array|null $serviceDtoData
     * @return void
     */
    protected function prepareServiceDtoBeforeTransform(?array &$serviceDtoData): void
    {
        $this->fields['statusFormatted'] = 'statusFormatted';


        $serviceDtoData['statusFormatted'] = null;

        if (!empty($serviceDtoData['status'])) {
            $status = $serviceDtoData['status'];
            $serviceDtoData['statusFormatted'] = $this->translator->trans('client.status.' . $status['symbol']);
        }

        if (isset($serviceDtoData['registerAddress'])) {
            $serviceDtoData['registerAddress']['building'] = $serviceDtoData['registerAddress']['houseNumber'];
            $serviceDtoData['registerAddress']['apartment'] = $serviceDtoData['registerAddress']['apartmentNumber'];
            unset($serviceDtoData['registerAddress']['name']);
            unset($serviceDtoData['registerAddress']['houseNumber']);
            unset($serviceDtoData['registerAddress']['apartmentNumber']);
        } else {
            $serviceDtoData['registerAddress'] = null;
        }
    }
}
