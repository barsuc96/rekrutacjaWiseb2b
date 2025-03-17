<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Security\Service\Interfaces\PasswordChangeServiceInterface;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;

class PasswordChangeService implements PasswordChangeServiceInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    /**
     * @throws ObjectValidationException
     */
    public function __invoke(CommonServiceDTO $passwordChangeServiceDto): CommonServiceDTO
    {
        $data = $passwordChangeServiceDto->read();

        $oldPassword = $data['oldPassword'] ?? null;
        $newPassword = $data['newPassword'] ?? null;

        $loggedUser = $this->security->getUser();

        if ($this->userPasswordHasher->isPasswordValid($loggedUser, $oldPassword) === false) {
            throw new ObjectValidationException('Nieprawidłowę stare hasło');
        }

        /** @var User $user */
        $user = $this->userRepository->find($loggedUser->getId());

        $hashNewPassword = $this->userPasswordHasher->hashPassword($user, $newPassword);

        $user
            ->setPassword($hashNewPassword)
        ;

        $user = $this->userRepository->save($user);

        ($resultDTO = new CommonServiceDTO())->write($user);

        return $resultDTO;
    }
}
