<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Traders;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\User\ApiAdmin\Dto\Traders\PutTraderDto;
use Wise\User\ApiAdmin\Service\Traders\Interfaces\PutTradersServiceInterface;
use Wise\User\Service\Trader\Interfaces\AddOrModifyTraderServiceInterface;

class PutTradersService extends AbstractPutService implements PutTradersServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyTraderServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutTraderDto $putDto
     *
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutTraderDto)) {
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
