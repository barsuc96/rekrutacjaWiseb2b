<?php

declare(strict_types=1);

namespace Wise\Security;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Wise\Core\WiseBaseExtension;

class WiseSecurityExtension extends WiseBaseExtension
{
    const ALIAS = 'wise_security';

    const OAUTH_API_CLIENT_ID_SESSION_PARAM = 'oauth_api_client_id';

    const OAUTH_ACCESS_TOKEN_SESSION_PARAM = 'oauth_access_token';

    public function getConfiguration(array $config, ContainerBuilder $container): WiseSecurityConfiguration
    {
        return new WiseSecurityConfiguration();
    }
}
