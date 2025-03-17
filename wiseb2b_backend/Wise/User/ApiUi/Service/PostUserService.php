<?php

declare(strict_types=1);

namespace Wise\User\ApiUi\Service;

use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Dto\CommonPostUiApiDto;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\ApiUi\ServiceInterface\ApiUiPostServiceInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\User\ApiUi\Dto\Users\PostUserRequestDto;
use Wise\User\ApiUi\Service\Interfaces\PostUserServiceInterface;
use Wise\User\Service\User\Interfaces\AddNewUserServiceInterface;

/** @implements ApiUiPostServiceInterface<PostUserRequestDto> */
class PostUserService extends AbstractPostService implements PostUserServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        protected readonly TranslatorInterface $translator,
        protected readonly AddNewUserServiceInterface $service,
    ) {
        parent::__construct($sharedActionService);
    }

    public function post(CommonPostUiApiDto $postUserDto): void
    {
        /** @var $dto PostUserRequestDto */
        ($serviceDto = new CommonModifyParams())->write($postUserDto);

        $result = ($this->service)($serviceDto)->read();

        $this->setParameters($this->translator->trans('user.created'))->setData($result);
    }
}
