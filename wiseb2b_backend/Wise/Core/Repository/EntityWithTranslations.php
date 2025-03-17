<?php

namespace Wise\Core\Repository;

interface EntityWithTranslations
{
    public function getTranslationClass(): string;

    public function getTranslationEntityIdField(): string;
}
