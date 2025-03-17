<?php

declare(strict_types=1);

namespace Wise\Security\Service;

use Doctrine\ORM\EntityManagerInterface;
use League\Bundle\OAuth2ServerBundle\Model\AccessToken;
use League\Bundle\OAuth2ServerBundle\Model\RefreshToken;
use Symfony\Component\Security\Core\Security;
use Wise\Core\Dto\CommonServiceDTO;
use Wise\Security\Repository\Doctrine\AccessTokenRepository;
use Wise\Security\Repository\Doctrine\RefreshTokenRepository;
use Wise\Security\Service\Interfaces\LogoutServiceInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class LogoutService implements LogoutServiceInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
        private readonly ManagerRegistry $managerRegistry
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(CommonServiceDTO $logoutServiceDto): CommonServiceDTO
    {
        $data = $logoutServiceDto->read();

        //Ponieważ nie można extendowć repo AccessTokenRepository po stronie biblioteki oauth2, a sama biblioteka
        //nie ma niezbędnych metod któe są potrzebnę do znalezienia i pobrania wszystkich tokenów
        //dla zalogowanego użytkownia, to tworzymy nowe własne repozytoria i podpinamy je do encji biblioteki,
        //w tym wypadku to będą encję AccessToken i RefreshToken
        $accessTokenRepository = new AccessTokenRepository($this->managerRegistry,AccessToken::class);

        $refreshTokenRepository = new RefreshTokenRepository($this->entityManager, $this->entityManager->getClassMetadata(RefreshToken::class));

        $loggedUser = $this->security->getUser();

        //Jeśli zalogowany użytkownik istniję, to cofamy wszystkie znalezionę tokeny i refresz tokeny
        if ($loggedUser) {
            $tokens = $accessTokenRepository->findByUserIdentifier($loggedUser->getUserIdentifier());

            /** @var AccessToken $token */
            foreach ($tokens as $token) {
                $token->revoke();

                /** @var RefreshToken $refreshToken */
                $refreshToken = $refreshTokenRepository->findByAccessToken($token->getIdentifier());

                if ($refreshToken) {
                    $refreshToken->revoke();

                    $this->entityManager->persist($refreshToken);
                }

                $this->entityManager->persist($token);
            }
        }

        return new CommonServiceDTO();
    }
}
