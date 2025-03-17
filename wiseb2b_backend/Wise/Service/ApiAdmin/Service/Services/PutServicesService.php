<?php

declare(strict_types=1);

namespace Wise\Service\ApiAdmin\Service\Services;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Service\ApiAdmin\Dto\Services\PutServiceDto;
use Wise\Service\ApiAdmin\Service\Services\Interfaces\PutServicesServiceInterface;
use Wise\Service\Service\Service\Interfaces\AddOrModifyServiceServiceInterface;

class PutServicesService extends AbstractPutService implements PutServicesServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyServiceServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutServiceDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutServiceDto)) {
            throw new InvalidArgumentException(
                'Niepoprawne DTO otrzymane na wejście requesta: ' . $putDto::class
            );
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'idExternal',
            'internalId' => 'id',
        ]);

        $serviceDTO->setMergeNestedObjects($isPatch);
        $serviceDTO = ($this->service)($serviceDTO, $isPatch);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        return (new CommonObjectIdResponseDto())
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);
    }
}
