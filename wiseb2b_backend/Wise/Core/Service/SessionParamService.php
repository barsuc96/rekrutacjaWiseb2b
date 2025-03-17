<?php

declare(strict_types=1);

namespace Wise\Core\Service;

use Symfony\Component\Security\Core\Security;
use Wise\Core\Domain\SessionParam;
use Wise\Core\Repository\Doctrine\SessionParamRepositoryInterface;
use Wise\Security\ApiUi\Model\UserLoginInfo;

class SessionParamService implements SessionParamServiceInterface
{
    public function __construct(
        private SessionParamRepositoryInterface $sessionParamRepository,
        private Security $security
    ) {
    }

    public function getActiveSessionParam(string $symbol): ?SessionParam
    {
        return $this->sessionParamRepository->findOneBy([
            'sessionId' => $this->getCurrentSessionId(),
            'symbol' => $symbol,
            'isActive' => true,
        ]);
    }

    public function checkSessionParamExists(string $symbol): bool
    {
        return (bool)$this->sessionParamRepository->findOneBy([
            'sessionId' => $this->getCurrentSessionId(),
            'symbol' => $symbol,
            'isActive' => true,
        ]);
    }

    public function setSessionParam(string $symbol, string $value): void
    {
        $sessionParam = $this->getActiveSessionParam($symbol);

        if ($sessionParam !== null) {
            $sessionParam->setValue($value);
            $sessionParam->setUpdateDate();
            $this->sessionParamRepository->save($sessionParam, true);
            return;
        }

        $sessionParam = new SessionParam();
        $sessionParam->setSessionId($this->getCurrentSessionId());
        $sessionParam->setSymbol($symbol);
        $sessionParam->setValue($value);
        $sessionParam->setIsActive(true);

        $this->sessionParamRepository->save($sessionParam, true);
    }

    public function deactivateSessionParam(string $symbol): void
    {
        $sessionParam = $this->sessionParamRepository->findOneBy([
            'sessionId' => $this->getCurrentSessionId(),
            'symbol' => $symbol,
            'isActive' => true,
        ]);

        if ($sessionParam instanceof SessionParam) {
            $sessionParam->setIsActive(false);
            $sessionParam->setUpdateDate();
            $this->sessionParamRepository->save($sessionParam, true);
        }
    }

    protected function getCurrentSessionId(): string
    {
        /** @var UserLoginInfo $user */
        $user = $this->security->getUser();
        return $user->getCurrentSessionId();
    }
}
