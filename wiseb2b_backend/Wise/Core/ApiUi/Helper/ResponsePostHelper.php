<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\ApiAdmin\Enum\ResponseStatusEnum as ApiResponseStatusEnum;
use Wise\Core\ApiUi\Dto\Common200FormResponseDto;
use Wise\Core\Enum\ResponseMessageStyle;

/**
 * Helper wspomagający response dla dedykowanych endpointów
 */
class ResponsePostHelper
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ){}

    public function prepareAuthorizationFailedResponse(
        array $fieldsInfo = [],
    ): JsonResponse {
        return (new Common200FormResponseDto(
            status: ApiResponseStatusEnum::SUCCESS->value,
            message: $this->translator->trans('security.login.failed'),
            messageStyle: ResponseMessageStyle::FAILED->value,
            showMessage: true,
            showModal: false
        ))->setFieldsInfo([])->setData([])->jsonSerialize();
    }
}
