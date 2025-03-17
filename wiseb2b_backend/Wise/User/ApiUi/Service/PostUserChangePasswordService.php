<?php

namespace Wise\User\ApiUi\Service;

use Webmozart\Assert\Assert;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\User\ApiUi\Dto\Users\PostUserChangePasswordRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserChangePasswordInterface;
use Wise\User\Service\User\Interfaces\ChangePasswordForCurrentUserServiceInterface;

/**
 * Obsługa zmiany hasła
 */
class PostUserChangePasswordService extends AbstractPostService implements PostUserChangePasswordInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        NotificationManagerInterface $notificationManager,
        private readonly ChangePasswordForCurrentUserServiceInterface $service,
    ) {
        parent::__construct($sharedActionService, $notificationManager);
    }

    /** @var PostUserChangePasswordRequestDto $dto */
    public function post(AbstractDto $dto): void
    {
        Assert::isInstanceOf($dto, PostUserChangePasswordRequestDto::class);

        $this->serviceDtoWrite($dto);
        ($this->service)($this->serviceDto);

        $this->setParameters($this->sharedActionService->translate('user.password_changed'));
    }
}
