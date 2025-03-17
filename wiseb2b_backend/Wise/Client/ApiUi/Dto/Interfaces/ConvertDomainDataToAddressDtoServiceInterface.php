<?php

namespace Wise\Client\ApiUi\Dto\Interfaces;

interface ConvertDomainDataToAddressDtoServiceInterface
{
    public function __invoke(array $serviceDtoData): array;
}
