<?php

namespace Wise\Core\Service;

use Symfony\Component\Console\Output\OutputInterface;
use Wise\Core\Service\Interfaces\ReplicationRequestStatsSenderServiceInterface;

/**
 * Serwis naprawiający problemy z integracją
 */
class ReplicationRequestStatsSenderService implements ReplicationRequestStatsSenderServiceInterface
{

    public function __construct(

    ){}



    public function __invoke(?OutputInterface $output = null): void
    {

    }
}
