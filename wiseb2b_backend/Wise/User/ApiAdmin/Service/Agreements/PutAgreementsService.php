<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Agreements;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\User\ApiAdmin\Dto\Agreements\PutAgreementDto;
use Wise\User\ApiAdmin\Service\Agreements\Interfaces\PutAgreementsServiceInterface;
use Wise\User\Service\Agreement\Interfaces\AddOrModifyAgreementServiceInterface;

class PutAgreementsService extends AbstractPutService implements PutAgreementsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyAgreementServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutAgreementDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutAgreementDto)) {
            throw new InvalidArgumentException(
                'Niepoprawne DTO otrzymane na wejście requesta: ' . $putDto::class
            );
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'symbol',
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
