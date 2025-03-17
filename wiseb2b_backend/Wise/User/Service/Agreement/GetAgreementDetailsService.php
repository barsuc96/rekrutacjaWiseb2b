<?php

declare(strict_types=1);

namespace Wise\User\Service\Agreement;

use Wise\Core\Service\AbstractDetailsService;
use Wise\User\Domain\Agreement\AgreementRepositoryInterface;
use Wise\User\Service\Agreement\Interfaces\GetAgreementDetailsServiceInterface;

class GetAgreementDetailsService extends AbstractDetailsService implements GetAgreementDetailsServiceInterface
{
    public function __construct(
        private readonly AgreementRepositoryInterface $repository,
    ){
        parent::__construct($repository);
    }
}
