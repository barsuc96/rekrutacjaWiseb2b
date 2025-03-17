<?php

declare(strict_types=1);

namespace Wise\Core\ApiAdmin\Service;

use Wise\Core\Api\Service\AbstractPresentationService;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

/**
 * Klasa bazowa dla serwisów prezentacji ADMIN API
 */
abstract class AbstractAdminApiService extends AbstractPresentationService
{
    protected AdminApiShareMethodsHelper|CommonApiShareMethodsHelper $sharedActionService;

    public function __construct(
        AdminApiShareMethodsHelper $sharedActionService,
        private readonly ApplicationServiceInterface $applicationService
    ){
        $this->sharedActionService = $sharedActionService;
        parent::__construct($sharedActionService, $applicationService);
    }

    /**
     * Rozpoczęcie przetwarzania obiektu
     * @return void
     */
    protected function startProcessing(): void
    {
        $this->sharedActionService->repositoryManager->beginTransaction();
    }

    /**
     * Walidacja obiektu
     * @param AbstractDto $object
     * @return void
     */
    protected function validateObject(AbstractDto $object): void
    {
        $this->sharedActionService->objectValidator->validate($object);
    }
}
