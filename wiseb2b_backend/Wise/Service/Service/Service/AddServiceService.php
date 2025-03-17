<?php

declare(strict_types=1);

namespace Wise\Service\Service\Service;

use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\Service\Domain\Service\Factory\ServiceFactory;
use Wise\Service\Domain\Service\ServiceRepositoryInterface;
use Wise\Service\Service\Service\Interfaces\AddServiceServiceInterface;

class AddServiceService extends AbstractAddService implements AddServiceServiceInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $repository,
        private readonly ServiceFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }
}
