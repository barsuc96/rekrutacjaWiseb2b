<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Wise\Core\Service\AbstractAddOrModifyService;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\AddOrModifyUserServiceInterface;
use Wise\User\Service\User\Interfaces\AddUserServiceInterface;

class AddOrModifyUserService extends AbstractAddOrModifyService implements AddOrModifyUserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly AddUserServiceInterface $addService,
        private readonly ModifyUserService $modifyService,
    ) {
        parent::__construct($repository, $addService, $modifyService);
    }
}
