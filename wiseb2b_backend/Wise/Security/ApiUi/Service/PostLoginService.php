<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Enum\ResponseMessageStyle;
use Wise\Security\ApiUi\Dto\LoginDto;
use Wise\Security\ApiUi\Service\Interfaces\PostLoginServiceInterface;
use Wise\Security\Service\Interfaces\CanLoginServiceInterface;

/**
 * Obsługuje logowanie użytkownika
 * Zajmuje się jedynie walidacją danych w celu zwrócenia poprawnego wyniku w komunikacie dla użytkownika (resztą zajmuje się oAuth czyli zwróceniem tokenu)
 */
class PostLoginService extends AbstractPostService implements PostLoginServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly CanLoginServiceInterface $loginService,
        private readonly TranslatorInterface $translator
    ){
        parent::__construct($sharedActionService);
    }
    public function post(LoginDto|CommonPostUiApiDto $dto): void
    {
        if(!empty($dto->getUsername())){
            $dto->setUsername(strtolower($dto->getUsername()));
        }

        $commonServiceDTO = new CommonServiceDTO();
        $commonServiceDTO->write($dto);

        $result = ($this->loginService)($commonServiceDTO);

        if($result){
            $resultMessage = $this->translator->trans('security.login.success');
        }else{
            $resultMessage = $this->translator->trans('security.login.failed');
        }

        $this->setParameters(message: $resultMessage, messageStyle: ResponseMessageStyle::FAILED)->setData(['result' => $result]);
    }
}
