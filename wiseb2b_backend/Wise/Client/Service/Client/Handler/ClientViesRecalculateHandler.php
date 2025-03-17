<?php

declare(strict_types=1);

namespace Wise\Client\Service\Client\Handler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Wise\Client\Service\Client\Command\ViesRecalculateForClientCommand;
use Wise\Client\Service\Client\Interfaces\VerifyClientViesInformationServiceInterface;
use Wise\Client\Service\Client\VerifyClientViesInformationServiceParams;

#[AsMessageHandler]
class ClientViesRecalculateHandler
{
    public function __construct(
        private readonly VerifyClientViesInformationServiceInterface $verifyClientViesInformationService,
    ){}

    public function __invoke(ViesRecalculateForClientCommand $client): void
    {
        $params = new VerifyClientViesInformationServiceParams();
        $params->setClientId($client->getClientId());
        ($this->verifyClientViesInformationService)($params);
    }
}
