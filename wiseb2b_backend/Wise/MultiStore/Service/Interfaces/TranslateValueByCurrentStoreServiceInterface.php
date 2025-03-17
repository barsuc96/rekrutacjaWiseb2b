<?php

namespace Wise\MultiStore\Service\Interfaces;

interface TranslateValueByCurrentStoreServiceInterface
{
    public function __invoke(string $moduleName, string $key, ?int $storyId = null): string;
}
