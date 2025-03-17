<?php

namespace Wise\User\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\CommonLogicException;
use Wise\User\Domain\User\Exceptions\LastPasswordIsNotCorrectException;
use Wise\User\Domain\User\Exceptions\UserNotExistException;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\ChangePasswordServiceInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;

/**
 * Serwis obsługujący zmianę hasła użytkownika
 */
class ChangePasswordService implements ChangePasswordServiceInterface
{
    public function __construct(
        private readonly ModifyUserServiceInterface $service,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly UserRepositoryInterface $userRepository,
    ){}

    public function __invoke(ChangePasswordParams $changePasswordParams): CommonServiceDTO
    {
        $user = $this->userRepository->find($changePasswordParams->getUserId());
        $this->validate($changePasswordParams, $user);

        $serviceParams = new CommonModifyParams();
        $serviceParams->writeAssociativeArray([
            'id' => $changePasswordParams->getUserId(),
            'password' => $changePasswordParams->getNewPassword(),
        ]);

        $serviceParams->setMergeNestedObjects(true);
        return ($this->service)($serviceParams);
    }

    /**
     * Walidacja danych
     * @param ChangePasswordParams $changePasswordParams
     * @param User $user
     * @return void
     */
    protected function validate(ChangePasswordParams $changePasswordParams, ?User $user): void
    {
        if($user == null){
            throw UserNotExistException::id($changePasswordParams->getUserId());
        }

        // Sprawdzenie, czy stare hasło jest poprawne
        if(!$this->userPasswordHasher->isPasswordValid($user, $changePasswordParams->getLastPassword())){
            throw new LastPasswordIsNotCorrectException();
        }

        // Sprawdzenie, czy potwierdzenie i nowe haslo jest takie same
        if($changePasswordParams->getNewPassword() !== $changePasswordParams->getRepeatNewPassword()){
            throw (new CommonLogicException())->setTranslation('user.password_confirm_not_same');
        }

        // Sprawdzenie, czy nowe hasło nie jest takie samo jak stare
        if($changePasswordParams->getLastPassword() === $changePasswordParams->getNewPassword()) {
            throw (new CommonLogicException())->setTranslation('user.password_changed_same_password');
        }
    }
}
