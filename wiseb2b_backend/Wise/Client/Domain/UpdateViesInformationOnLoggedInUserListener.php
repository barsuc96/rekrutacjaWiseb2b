<?php

declare(strict_types=1);

namespace Wise\Client\Domain;

use DateInterval;
use Wise\Client\Service\Client\GetClientDetailsParams;
use Wise\Client\Service\Client\Interfaces\GetClientDetailsServiceInterface;
use Wise\Client\Service\Client\Interfaces\VerifyClientViesInformationServiceInterface;
use Wise\Client\Service\Client\VerifyClientViesInformationServiceParams;
use Wise\Core\Model\QueryFilter;
use Wise\Core\Repository\RepositoryManagerInterface;
use Wise\User\Domain\User\Events\UserLoggedInEvent;

/**
 * Podczas logowania użytkownika sprawdzamy czy klient posiada aktualne informacje z Vies
 */
class UpdateViesInformationOnLoggedInUserListener
{
    public function __construct(
//        private readonly VerifyClientViesInformationServiceInterface $verifyClientViesInformationService,
//        private readonly GetClientDetailsServiceInterface $getClientDetailsService,
//        private readonly RepositoryManagerInterface $repositoryManager
    ){}

    /**
     *
     */
    public function __invoke(UserLoggedInEvent $event): void
    {
//        $params = new GetClientDetailsParams();
//        $params
//            ->setFilters([
//                new QueryFilter('userId.id', $event->getId()),
//            ])
//            ->setFields([
//                'id' => 'id',
//                'isVies' => 'isVies',
//                'viesLastUpdate' => 'viesLastUpdate',
//                'userId' => 'userId.id'
//            ]);
//
//        $clientDetails = ($this->getClientDetailsService)($params)->read();
//
//        $oneDayLater = !empty($clientDetails['viesLastUpdate']) ? (clone $clientDetails['viesLastUpdate'])->add(new DateInterval('P1D')) : null;
//        $currentDate = new \DateTime();
//
//        // Jeśli klient nie posiada informacji z Vies lub informacje są starsze niż 1 dzień, aktualizujemy
//        if($clientDetails['viesLastUpdate'] === null || $oneDayLater === null || $currentDate > $oneDayLater){
//
//            // Weryfikacja Vies
//            $params = new VerifyClientViesInformationServiceParams();
//            $params->setClientId($clientDetails['id']);
//
//            ($this->verifyClientViesInformationService)($params);
//            $this->repositoryManager->flush();
//        }
    }
}
