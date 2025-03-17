<?php

declare(strict_types=1);

namespace Wise\Security\ApiUi\Service;

use InvalidArgumentException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiUi\Helper\UiApiShareMethodsHelper;
use Wise\Core\ApiUi\Service\AbstractPostService;
use Wise\Core\Dto\AbstractDto;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\ApiUi\Dto\PostPasswordResetDto;
use Wise\Security\ApiUi\Service\Interfaces\PostPasswordResetServiceInterface;
use Wise\Security\Service\Interfaces\PasswordResetServiceInterface;

class PostPasswordResetService extends AbstractPostService implements PostPasswordResetServiceInterface
{
    public function __construct(
        UiApiShareMethodsHelper $sharedActionService,
        private readonly PasswordResetServiceInterface $service,
        private readonly TranslatorInterface $translator
    ) {
        parent::__construct($sharedActionService);
    }

    /**
     * @param PostPasswordResetDto $dto
     */
    public function post(AbstractDto $dto): void
    {
        if (!($dto instanceof PostPasswordResetDto)) {
            throw new InvalidArgumentException('Niepoprawne DTO otrzymane na wejście requesta: '.$dto::class);
        }

        ($serviceDTO = new CommonServiceDTO())->write($dto);

        $serviceDTO = ($this->service)($serviceDTO);

        $this->setParameters(
            message: $this->translator->trans('security.password_reset.success')
        )->setData(['id' => $serviceDTO->read()['id']]);
    }
}
