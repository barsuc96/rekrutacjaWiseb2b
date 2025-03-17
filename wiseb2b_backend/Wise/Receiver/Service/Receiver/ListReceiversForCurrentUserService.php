<?php

declare(strict_types=1);

namespace Wise\Receiver\Service\Receiver;

use Exception;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Service\AbstractForCurrentUserService;
use Wise\Core\Service\CommonListParams;
use Wise\Core\Service\ValidatedUserTrait;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversForCurrentUserServiceInterface;
use Wise\Receiver\Service\Receiver\Interfaces\ListReceiversServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\Core\Service\CommonListResult;

/**
 * Serwis - pobierający listę odbiorców w zależności od klienta zalogowanego użytkownika
 */
class ListReceiversForCurrentUserService extends AbstractForCurrentUserService implements ListReceiversForCurrentUserServiceInterface
{
    const HAS_USER_ID_FIELD = false;

    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly ?ListReceiversServiceInterface $service,
    ) {
        parent::__construct($currentUserService, $service);
    }
}
