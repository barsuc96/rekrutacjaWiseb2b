<?php

namespace Wise\Core\Service;

use Symfony\Component\Console\Output\OutputInterface;
use Wise\Core\Service\Interfaces\IntegrationFixerServiceInterface;

/**
 * Serwis naprawiający problemy z integracją
 */
class IntegrationFixerService implements IntegrationFixerServiceInterface
{

    public function __construct(){}



    public function __invoke(?OutputInterface $output = null): void
    {

    }
}
