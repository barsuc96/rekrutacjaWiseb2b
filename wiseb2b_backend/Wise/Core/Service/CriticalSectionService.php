<?php

namespace Wise\Core\Service;

use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Wise\Core\Service\Interfaces\CriticalSectionServiceInterface;

class CriticalSectionService implements CriticalSectionServiceInterface
{
    /** @var LockInterface[] */
    private array $locks;

    public function __construct(
        private readonly LockFactory $lockFactory,
    )
    {
    }

    public function lock(string $string): void
    {
        $this->locks[$string] = $this->lockFactory->createLock($string);
        $this->locks[$string]->acquire(true);
    }


    public function unlock(string $string): void
    {
        $this->locks[$string]->release();
    }
}