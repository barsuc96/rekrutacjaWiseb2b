<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Service\OverLoginUserParams;
use Wise\Core\ServiceInterface\CoreAutoOverloginUserServiceInterface;
use Wise\Security\Service\Interfaces\CurrentUserServiceInterface;
use Wise\Security\Service\Interfaces\OverLogInUserServiceInterface;
use Wise\Security\Service\Interfaces\OverLogOutUserServiceInterface;

class CoreAutoOverloginUserService implements CoreAutoOverloginUserServiceInterface
{
    public function __construct(
        private readonly CurrentUserServiceInterface $currentUserService,
        private readonly OverLogInUserServiceInterface $overLoginUserService,
        private readonly OverLogOutUserServiceInterface $overLogOutUserService
    ) {}

    public function __invoke(OverLoginUserParams $overLoginUserParams): CommonServiceDTO
    {
        $currentLoggedUser = $this->currentUserService->getCurrentUser();
        $wantToOverLogUserId = $overLoginUserParams->getUserId();

        // Chcemy obsługiwać przełączanie użytkownika
        if($wantToOverLogUserId !== null){

            // Jeśli użytkownik na którego chcemy się przełączyć jest inny od aktualnie zalogowanego
            // to przełączamy na tego użytkownika
            if($currentLoggedUser->getId() !== $wantToOverLogUserId){
                ($this->overLoginUserService)($overLoginUserParams);
            }

        }else{
            $currentLoggedUser = $this->currentUserService->getCurrentUser();

            // Jeśli uż
            if($currentLoggedUser->getOverlogged() !== null){
                ($this->overLogOutUserService)();
            }
        }
        return new CommonServiceDTO();
    }
}
