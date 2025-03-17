<?php

namespace Wise\User\Domain\User\Listener;

use Wise\User\Domain\User\Events\UserHasChangedEvent;
use Wise\User\Service\User\Interfaces\SendEmailToAdministratorAboutRegisterUserServiceInterface;
use Wise\User\Service\User\SendEmailToAdministratorAboutRegisterUserParams;

class SendEmailToAdministratorAboutRegisterUserListener
{
    public function __construct(
        private readonly SendEmailToAdministratorAboutRegisterUserServiceInterface $sendEmailToAdministratorAboutRegisterUserService
    ) {}

    public function __invoke(UserHasChangedEvent $event): void
    {
        $params = new SendEmailToAdministratorAboutRegisterUserParams();
        $params->setUserId($event->getId());

        ($this->sendEmailToAdministratorAboutRegisterUserService)($params);
    }
}
