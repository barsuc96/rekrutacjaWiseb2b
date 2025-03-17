<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\UserAgreements;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\User\ApiAdmin\Dto\UserAgreements\PutUserAgreementDto;
use Wise\User\ApiAdmin\Service\UserAgreements\Interfaces\PutUserAgreementsServiceInterface;
use Wise\User\Service\UserAgreement\Interfaces\AddOrModifyUserAgreementServiceInterface;

class PutUserAgreementsService extends AbstractPutService implements PutUserAgreementsServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyUserAgreementServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutUserAgreementDto $putDto
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutUserAgreementDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta');
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'idExternal',
            'internalId' => 'id',
            'userId' => 'userExternalId',
            'userInternalId' => 'userId',
            'agreementId' => 'agreementExternalId',
            'agreementInternalId' => 'agreementId',
        ]);

        $serviceDTO->setMergeNestedObjects($isPatch);
        $serviceDTO = ($this->service)($serviceDTO, $isPatch);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        $response = (new CommonObjectIdResponseDto());
        $response->prepareFromData($putDto);
        $response
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);

        return $response;
    }
}
