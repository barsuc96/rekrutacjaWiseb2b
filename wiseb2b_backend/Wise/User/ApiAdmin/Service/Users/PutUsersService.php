<?php

declare(strict_types=1);

namespace Wise\User\ApiAdmin\Service\Users;

use InvalidArgumentException;
use Wise\Core\ApiAdmin\Dto\CommonObjectIdResponseDto;
use Wise\Core\ApiAdmin\Helper\AdminApiShareMethodsHelper;
use Wise\Core\ApiAdmin\Service\AbstractPutService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonModifyParams;
use Wise\User\ApiAdmin\Dto\Users\PutUserDto;
use Wise\User\ApiAdmin\Service\Users\Interfaces\PutUsersServiceInterface;
use Wise\User\Service\User\Interfaces\AddOrModifyUserServiceInterface;

class PutUsersService extends AbstractPutService implements PutUsersServiceInterface
{
    public function __construct(
        AdminApiShareMethodsHelper $adminApiShareMethodsHelper,
        private readonly AddOrModifyUserServiceInterface $service,
    ) {
        parent::__construct($adminApiShareMethodsHelper);
    }

    /**
     * @param PutUserDto $putDto
     *
     * @return CommonObjectIdResponseDto
     */
    public function put(AbstractDto $putDto, bool $isPatch = false): CommonObjectIdResponseDto
    {
        if (!($putDto instanceof PutUserDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta');
        }

        ($serviceDTO = new CommonModifyParams())->write($putDto, [
            'id' => 'idExternal',
            'internalId' => 'id',
            'clientId' => 'clientExternalId',
            'clientInternalId' => 'clientId',
            'roleId' => 'roleExternalId',
            'roleInternalId' => 'roleId',
            'traderId' => 'traderExternalId',
            'traderInternalId' => 'traderId',
        ]);

        $serviceDTO->setMergeNestedObjects($isPatch);
        $serviceDTO = ($this->service)($serviceDTO, $isPatch);

        $this->adminApiShareMethodsHelper->repositoryManager->flush();

        return (new CommonObjectIdResponseDto())
            ->setInternalId($serviceDTO->read()['id'] ?? null)
            ->setId($serviceDTO->read()['idExternal'] ?? null);
    }
}
