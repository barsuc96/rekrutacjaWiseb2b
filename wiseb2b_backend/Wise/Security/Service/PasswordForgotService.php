<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\Service\Interfaces\PasswordForgotServiceInterface;


/**
 * Serwis wysyłający email z linkiem do resetowania hasła
 */
class PasswordForgotService implements PasswordForgotServiceInterface
{
    public function __construct() {}

    /**
     * @param CommonServiceDTO $passwordForgotServiceDto
     * @return CommonServiceDTO
     */
    public function __invoke(CommonServiceDTO $passwordForgotServiceDto): CommonServiceDTO
    {

        return new CommonServiceDTO();
    }
}
