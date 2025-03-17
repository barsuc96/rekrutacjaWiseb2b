<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;
use Wise\Service\Domain\Service\Exceptions\ServiceNotFoundExceptions;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\ModifyServiceServiceInterface;

class ModifyServiceService extends AbstractModifyService implements ModifyServiceServiceInterface
{
    protected const OBJECT_NOT_FOUND_EXCEPTION = ServiceNotFoundExceptions::class;

    public function __construct(
        private readonly ServiceRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }
}
