<?php

declare(strict_types=1);

namespace Wise\User\Service\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Wise\Client\Service\Client\Helper\Interfaces\ClientHelperInterface;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Helper\PersistenceShareMethodsHelper;
use Wise\Core\Service\AbstractModifyService;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Wise\User\Domain\User\UserRoleEnum;
use Wise\User\Service\Trader\Interfaces\TraderHelperInterface;
use Wise\User\Service\User\Interfaces\ModifyUserServiceInterface;

class ModifyUserService extends AbstractModifyService implements ModifyUserServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly PersistenceShareMethodsHelper $persistenceShareMethodsHelper,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly ClientHelperInterface $clientHelper,
        private readonly TraderHelperInterface $traderHelper
    ){
        parent::__construct($repository, $persistenceShareMethodsHelper);
    }

    /**
     * Przygotowanie danych przed połączeniem ich z encją za pomocą Merge Service
     * @param array|null $data
     * @param AbstractEntity $entity
     * @return void
     */
    protected function prepareDataBeforeMergeData(?array &$data, User|AbstractEntity $entity): void
    {
        $this->clientHelper->prepareExternalData($data);
        $this->traderHelper->prepareExternalData($data);

        if($entity->getRoleId() === null && empty($data['roleId'])){
            $data['roleId'] = UserRoleEnum::ROLE_USER->value;
        }

        if(!empty($data['username'])){
            $data['username'] = strtolower($data['username']);
        }

        if(!empty($data['email'])){
            $data['email'] = strtolower($data['email']);
        }

        if(!empty($data['password'])){
            $data['password'] = $this->userPasswordHasher->hashPassword($entity, $data['password']);
        }
    }
}
