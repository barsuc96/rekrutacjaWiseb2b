<?php

declare(strict_types=1);

namespace Wise\Core\ApiUi\Helper;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Wise\Core\Helper\CommonApiShareMethodsHelper;
use Wise\Core\Notifications\NotificationManagerInterface;
use Wise\Core\Notifications\NotificationResponseDTOConverterServiceInterface;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\Core\Service\DomainEventsDispatcher;
use Wise\Core\Service\Merge\MergeService;
use Wise\Core\Service\Validator\ValidatorServiceInterface;
use Wise\Core\ServiceInterface\CoreAutoOverloginUserServiceInterface;
use Wise\Core\Validator\ObjectValidator;

class UiApiShareMethodsHelper extends CommonApiShareMethodsHelper
{
}
