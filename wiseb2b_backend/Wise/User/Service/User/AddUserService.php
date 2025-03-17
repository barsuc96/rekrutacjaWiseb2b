<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractAddService;
use Wise\MultiStore\Service\Interfaces\CurrentStoreServiceInterface;
use Wise\User\Domain\User\Factory\UserFactory;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;
use Wise\User\Service\User\Interfaces\AddUserServiceInterface;

class AddUserService extends AbstractAddService implements AddUserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly UserFactory $entityFactory,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ClientHelperInterface $clientHelper,
        private readonly TraderHelperInterface $traderHelper,
        private readonly CurrentStoreServiceInterface $currentStoreService
    ){
        parent::__construct($repository, $entityFactory, $persistenceShareMethodsHelper);
    }

    /**
     * Umożliwia przygotowanie danych do utworzenia encji w fabryce.
     * @param array|null $data
     * @return array
     */
    protected function prepareDataBeforeCreateEntity(?array &$data): array
    {
        $this->clientHelper->prepareExternalData($data);
        $this->traderHelper->prepareExternalData($data);

        if(empty($data['createDate'])){
            $data['createDate'] = new DateTime();
        }

        if(!empty($data['username'])){
            $data['username'] = strtolower($data['username']);
        }

        if(!empty($data['email'])){
            $data['email'] = strtolower($data['email']);
        }

        if(empty($data['roleId'])){
            $data['roleId'] = UserRoleEnum::ROLE_USER->value;
        }

        if(empty($data['storeId'])){
            $data['storeId'] = $this->currentStoreService->getCurrentStoreId();
        }

        return $data;
    }

    /**
     * Umożliwia wykonanie dodatkowych czynności po utworzeniu encji w fabryce.
     * @param AbstractEntity $entity
     * @param array|null $data
     * @return void
     */
    protected function prepareEntityAfterCreateEntity(AbstractEntity $entity, ?array &$data): void
    {
        if(!empty($data['password'])){
            $entity->setPassword($this->userPasswordHasher->hashPassword($entity, $data['password']));
        }
    }
}
