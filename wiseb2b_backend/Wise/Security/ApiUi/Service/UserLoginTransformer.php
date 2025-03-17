<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use Wise\Security\ApiUi\Model\UserLoginInfo;
use Wise\User\Domain\User\User;

class UserLoginTransformer implements UserLoginTransformerInterface
{
    public function transform(User $user, ?string $currentSessionId, ?bool $overlogged = null): UserLoginInfo
    {
        return new UserLoginInfo(
            $user->getId(),
            $user->getClientId(),
            $user->getLogin(),
            $user->getIdExternal(),
            [$user->getRoleId()],
            $user->getPassword(),
            $user->getSalt(),
            $currentSessionId,
            $overlogged,
            $user->getStoreId()
        );
    }
}
