<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Exception;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectExistsException;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Service\User\Interfaces\AddNewUserServiceInterface;
use Wise\User\Service\User\Interfaces\GetUserDetailsServiceInterface;

class AddUserForCurrentUserService implements AddNewUserServiceInterface
{
    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly GetUserDetailsServiceInterface $getUserDetailsService,
    ){}

    /**
     * @throws ObjectExistsException
     * @throws Exception
     */
    public function __invoke(CommonModifyParams $dto): CommonServiceDTO
    {
        $result = new CommonServiceDTO();
        $result->writeAssociativeArray([]);

        return $result;
    }
}
