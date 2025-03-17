<?php

namespace Wise\User\ApiUi\Service\PanelManagement\Users;

use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;

use Wise\Core\ApiUi\Service\AbstractPostUiApiService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\Service\Interfaces\PasswordForgotServiceInterface;
use Wise\User\ApiUi\Dto\PanelManagement\Users\PostPanelManagementUserPasswordResetDto;
use Wise\User\ApiUi\Service\PanelManagement\Users\Interfaces\PostPanelManagementUserPasswordResetServiceInterface;

class PostPanelManagementUserPasswordResetService extends AbstractPostUiApiService implements PostPanelManagementUserPasswordResetServiceInterface
{
    protected string $messageSuccessTranslation = 'user.success_send_password_reset';

    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly PasswordForgotServiceInterface $passwordForgotService
    ){
        parent::__construct($sharedActionService);
    }

    public function post(PostPanelManagementUserPasswordResetDto|AbstractDto $dto): void
    {
        $params = new CommonServiceDTO();
        $params
            ->writeAssociativeArray([
                'id' => $dto->getId(),
            ]);

        ($this->passwordForgotService)($params);


        // Tworzenie rezultatu zwracanego przez serwis
        $this->prepareResponse($dto, []);
    }
}
