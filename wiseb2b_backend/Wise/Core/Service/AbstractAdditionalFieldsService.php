<?php

namespace Wise\Core\Service;

use Webmozart\Assert\Assert;
use Wise\Core\Exception\CommonLogicException;
use Wise\Core\Service\Interfaces\ApplicationServiceInterface;

abstract class AbstractAdditionalFieldsService implements ApplicationServiceInterface
{
    protected const PROVIDER_INTERFACE = null;

    private iterable $providers;

    public function __construct(
//        #[TaggedIterator('details_provider.shopping_lists')]
        iterable $providers
    ) {
//        Assert::allIsInstanceOf($providers, static::PROVIDER_INTERFACE);
        $this->providers = $providers;
    }

    public function getFieldValue($entityId, array &$cacheData, string $fieldName): mixed
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($fieldName)) {
                return $provider->getFieldValue($entityId, $cacheData);
            }
        }

        throw new CommonLogicException('Field not supported: ' . $fieldName);
    }
}
