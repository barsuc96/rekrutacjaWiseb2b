<?php

declare(strict_types=1);

namespace Wise\User\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Repository\AbstractRepository;
use Wise\Core\Repository\Doctrine\GlobalAddressRepositoryInterface;
use Wise\User\Domain\User\User;
use Wise\User\Domain\User\UserRepositoryInterface;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    protected const ENTITY_CLASS = User::class;
    public const REGISTER_ADDRESS_ENTITY_FIELD_NAME = 'registerAddress';

    public function __construct(
        ManagerRegistry $registry,
        private readonly GlobalAddressRepositoryInterface $globalAddressRepository,
    ) {
        parent::__construct($registry);
    }

    public function find($id, $lockMode = null, $lockVersion = null): ?User
    {
        /** @var User $user */
        $user = parent::find($id, $lockMode, $lockVersion);

        if (null === $user) {
            return null;
        }

        $this->addGlobalRegisterAddress($user);

        return $user;
    }

    public function findAll(): array
    {
        /** @var User[] $users */
        $users = parent::findAll();
        foreach ($users as $user) {
            $this->addGlobalRegisterAddress($user);
        }

        return $users;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        /** @var User $user */
        $user = parent::findOneBy($criteria, $orderBy);
        if (null === $user) {
            return null;
        }

        $this->addGlobalRegisterAddress($user);

        return $user;
    }

    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null): array
    {
        /** @var User[] $users */
        $users = parent::findBy($criteria, $orderBy, $limit, $offset);

        foreach ($users as $user) {
            $this->addGlobalRegisterAddress($user);
        }

        return $users;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByUsernameOrEmail(string $username, ?int $storeId = null): ?User
    {
        $builder = $this->createQueryBuilder('user');

        $builder
            ->select('user')
            ->where('user.login = :username')
            ->orWhere('user.email = :username')
            ->andWhere('user.storeId = :storeId')
            ->setParameter('username', $username)
            ->setParameter('storeId', $storeId);

        return $builder->getQuery()->getOneOrNullResult();
    }

    public function findByQueryFiltersView(
        array $queryFilters,
        array $orderBy = null,
        $limit = null,
        $offset = null,
        ?array $fields = [],
        ?array $joins = [],
        ?array $aggregates = [],
    ): array {
        $fetchRegisterAddress = false;

        if (in_array(self::REGISTER_ADDRESS_ENTITY_FIELD_NAME, $fields)) {
            $fetchRegisterAddress = true;

            if (($key = array_search(self::REGISTER_ADDRESS_ENTITY_FIELD_NAME, $fields)) !== false) {
                unset($fields[$key]);
            }
        }

        $users = parent::findByQueryFiltersView(
            $queryFilters,
            $orderBy,
            $limit,
            $offset,
            $fields,
            $joins,
            $aggregates
        );

        /** @var User $user */
        foreach ($users as &$user) {
            if ($fetchRegisterAddress) {
                //Pobieramy dane adresowe
                $globalAddress = $this->globalAddressRepository->getGlobalAddress(
                    static::ENTITY_CLASS,
                    self::REGISTER_ADDRESS_ENTITY_FIELD_NAME,
                    (int)($user['t0_id'] ?? $user['id'])
                );

                if ($globalAddress) {
                    $user[self::REGISTER_ADDRESS_ENTITY_FIELD_NAME] = $globalAddress;
                }
            }
        }

        return $users;
    }

    /**
     * Do obiektu użytownika wypełniamy pole registerAddress
     */
    private function addGlobalRegisterAddress(User $user): void
    {
        $globalAddress = $this->globalAddressRepository->getGlobalAddressAsAddress(
            static::ENTITY_CLASS,
            self::REGISTER_ADDRESS_ENTITY_FIELD_NAME,
            $user->getId()
        );

        $user->setRegisterAddress($globalAddress);
    }

    public function save(AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $this->getEntityManager()->persist($entity);

        $this->globalAddressRepository->prepareAndSaveGlobalAddress(
            $entity,
            self::REGISTER_ADDRESS_ENTITY_FIELD_NAME
        );

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $entity;
    }
}
