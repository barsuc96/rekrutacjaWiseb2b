<?php

namespace Wise\Core\Helper;

use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\Service\Validator\ValidatorServiceInterface;

class PersistenceShareMethodsHelper
{
    public function __construct(
        public readonly MergeService $mergeService,
        public readonly DomainEventsDispatcher $eventsDispatcher,
        public readonly ValidatorServiceInterface $validatorService,
        public readonly RepositoryManagerInterface $repositoryManager
    ){}
}
