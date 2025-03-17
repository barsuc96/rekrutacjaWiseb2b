<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Wise\Security\Service\Interfaces\SetOauthApiClientIdServiceInterface;
use Wise\Security\WiseSecurityExtension;

/**
 * Serwis odpowiedzialny za weryfikacje logowanie użytkownika
 * Zwraca false gdy, z jakiegoś powodu użytkownik nie może się zalogować
 */
class SetOauthApiClientIdService implements SetOauthApiClientIdServiceInterface
{
    public function __construct(
        private RequestStack $requestStack,
    ){}

    public function __invoke(string $apiClientId): void
    {
        try {
            $session = $this->requestStack->getSession();
            $session->set(WiseSecurityExtension::OAUTH_API_CLIENT_ID_SESSION_PARAM, $apiClientId);
        } catch (\Exception $e) {
            // obejście problemów przy wywołaniu z konsoli i testach
        }
    }
}
