<?php

namespace Wise\Core\Service\Interfaces;

interface ConfigServiceInterface
{
    public function get(mixed $key, bool $returnKeyWhenNotExistsConfiguration = false, bool $returnOnlyCurrentStoreConfigWithoutRegularConfig = false): mixed;
}
