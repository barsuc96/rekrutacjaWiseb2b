<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\ApiUi\Dto\PostPasswordForgotDto;
use Wise\Security\ApiUi\Service\Interfaces\PostPasswordForgotServiceInterface;
use Wise\Security\Service\Interfaces\PasswordForgotServiceInterface;

class PostPasswordForgotService extends AbstractPostService implements PostPasswordForgotServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly PasswordForgotServiceInterface $service,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostPasswordForgotDto $dto
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostPasswordForgotDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: ' . $dto::class);
        }

        ($serviceDTO = new CommonServiceDTO())->write($dto);

        $serviceDTO = ($this->service)($serviceDTO);

        $this->setParameters(
            message: $this->translator->trans('security.password_reset.send_email')
        )->setData($serviceDTO->read());
    }
}
