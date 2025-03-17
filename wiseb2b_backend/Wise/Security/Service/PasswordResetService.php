<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Wise\Core\Dto\CommonModifyParams;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Core\Exception\ObjectValidationException;
use Wise\Message\Helper\UserGenerateTokenHelper;
use Wise\Security\Service\Interfaces\PasswordResetServiceInterface;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;

/**
 * Serwis obsługuje resetowanie hasła
 */
class PasswordResetService implements PasswordResetServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly ModifyUserServiceInterface $modifyUserService
    ) {}

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     * @throws ObjectValidationException
     */
    public function __invoke(CommonServiceDTO $passwordResetServiceDto): CommonServiceDTO
    {
        $data = $passwordResetServiceDto->read();

        $token = $data['token'] ?? null;
        $newPassword = $data['password'] ?? null;

        $tokenData = $this->getTokenData($token);

        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => (int)$tokenData['userId']]);

        //Jeśli użytkownik znaleziony i podesłany token jest prawidłowy to zmieniamy hasło
        if ($user && $this->checkUserToken($user->getEmail(), $tokenData['endTime'], $tokenData['token'])) {

            // Przygotowanie danych do zapisu
            $params = new CommonModifyParams();
            $params
                ->writeAssociativeArray([
                   'id' => $user->getId(),
                   'password' => $newPassword
                ]);

            // Modyfikacja użytkownika
            ($this->modifyUserService)($params);

            ($resultDTO = new CommonServiceDTO())->write($user);

            return $resultDTO;
        }

        throw new ObjectValidationException('Incorrect token');
    }

    /**
     * Zwraca informacje z Tokena
     * @param $data
     * @return array
     */
    private function getTokenData($data): array
    {
        $tokenData = [];

        $data = explode('.', $data);

        $tokenData['userId'] = $data[0] ?? null;
        $tokenData['endTime'] = $data[1] ?? null;
        $tokenData['token'] = $data[2] ?? null;

        return $tokenData;
    }

    /**
     * Sprawdza, czy token jest poprawny i zawarte w nim dane do realizacji zmiany hasła
     * @param $userEmail
     * @param $endTime
     * @param $tokenToCheck
     * @return bool
     */
    private function checkUserToken($userEmail, $endTime, $tokenToCheck): bool
    {
        [$endTime, $token] = UserGenerateTokenHelper::generate($userEmail, $endTime);

        if ($token === $tokenToCheck && time() < $endTime) {
            return true;
        }

        return false;
    }
}
