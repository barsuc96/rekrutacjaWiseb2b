<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Service\AbstractAddOrModifyService;
use Wise\Service\Domain\Service\Service;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Domain\Service\ServicesServiceInterface;
use Wise\Service\Service\Service\Interfaces\AddOrModifyServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\AddServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\ModifyServiceServiceInterface;
use Wise\Service\Service\Service\Interfaces\ServiceHelperInterface;

class AddOrModifyServiceService extends AbstractAddOrModifyService implements AddOrModifyServiceServiceInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $repository,
        private readonly AddServiceServiceInterface $addService,
        private readonly ModifyServiceServiceInterface $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
