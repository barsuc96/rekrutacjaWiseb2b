<?php

namespace Wise\Service\Service\Service;

use Wise\Core\Service\AbstractDetailsService;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\GetServiceDetailsServiceInterface;

class GetServiceDetailsService extends AbstractDetailsService implements GetServiceDetailsServiceInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
    ){
        parent::__construct($serviceRepository);
    }
}
