<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Wise\Core\Service\AbstractAdditionalFieldsService;
use Wise\User\Service\User\DataProvider\UserDetailsProviderInterface;

class UserAdditionalFieldsService extends AbstractAdditionalFieldsService
{
    protected const PROVIDER_INTERFACE = UserDetailsProviderInterface::class;

    public function __construct(
        #[TaggedIterator('details_provider.user')] iterable $providers
    ){
        parent::__construct($providers);
    }
}
