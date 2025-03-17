<?php

declare(strict_types=1);

namespace Wise\Client\ApiAdmin\Dto\Clients;

use Symfony\Component\Serializer\Annotation\Ignore;
use Wise\Client\ApiAdmin\Dto\ClientDeliveryMethods\PutClientDeliveryMethodDto;

class ClientDeliveryMethodAggregateDto extends PutClientDeliveryMethodDto
{
    #[Ignore]
    protected int $internalId;

    #[Ignore]
    protected string $clientId;

    #[Ignore]
    protected ?int $clientInternalId;

    #[Ignore]
    protected ?int $deliveryMethodInternalId;
}
