<?php

declare(strict_types=1);

namespace Wise\I18n\ApiAdmin\Service\CurrencyExchanges;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\I18n\ApiAdmin\Dto\CurrencyExchanges\PutCurrencyExchangeDto;
use Wise\I18n\ApiAdmin\Service\CurrencyExchanges\Interfaces\PutCurrencyExchangesServiceInterface;
use Wise\I18n\Service\CurrencyExchange\Interfaces\AddOrModifyCurrencyExchangeServiceInterface;

class PutCurrencyExchangesService extends AbstractPutService implements PutCurrencyExchangesServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyCurrencyExchangeServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutCurrencyExchangeDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutCurrencyExchangeDto)) {
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
