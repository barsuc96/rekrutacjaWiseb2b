<?php

namespace Wise\Agreement\Service\Agreement;

use Wise\Agreement\Service\Agreement\Exception\UserNotAccessToAgreementException;
use Wise\Agreement\Service\Agreement\Interfaces\CanUserAccessToAgreementServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;

/**
 * Serwis sprawdzający, czy użytkownik ma dostęp do zgód
 */
class CanUserAccessToAgreementService implements CanUserAccessToAgreementServiceInterface
{
    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
    ){}

    public function check(): void
    {
        if(!in_array(UserRoleEnum::ROLE_ADMIN->value, $this->currentUserService->getRoles())){
            throw new UserNotAccessToAgreementException();
        }
    }
}
