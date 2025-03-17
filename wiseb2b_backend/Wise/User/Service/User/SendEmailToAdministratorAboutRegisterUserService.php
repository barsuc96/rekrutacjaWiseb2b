<?php

namespace Wise\User\Service\User;

use Wise\User\Service\User\Interfaces\SendEmailToAdministratorAboutRegisterUserServiceInterface;

/**
 * Serwis wysyła email do administratora o rejestracji nowego użytkownika
 */
class SendEmailToAdministratorAboutRegisterUserService implements SendEmailToAdministratorAboutRegisterUserServiceInterface
{
    protected const URL_TO_EDIT_CLIENT = '/managment_panel/dynamic_page/PANEL_CLIENTS_EDITS?mode=EDIT&param_client_id=%clientId%';

    public function __construct()
    {
    }

    public function __invoke(SendEmailToAdministratorAboutRegisterUserParams $params): void
    {
    }
}
