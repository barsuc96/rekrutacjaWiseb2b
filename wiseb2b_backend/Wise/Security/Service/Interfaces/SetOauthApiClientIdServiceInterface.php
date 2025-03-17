<?php

namespace Wise\Security\Service\Interfaces;

interface SetOauthApiClientIdServiceInterface
{
    public function __invoke(string $apiClientId): void;
}
