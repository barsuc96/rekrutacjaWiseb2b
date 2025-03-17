<?php

declare(strict_types=1);

namespace Wise\Security\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\Security\Domain\Oauth2\ApiClient;
use Wise\Security\Domain\Oauth2\ApiClientRepositoryInterface;

/**
 * @extends ServiceEntityRepository<ApiClient>
 *
 * @method ApiClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiClient[]    findAll()
 * @method ApiClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiClientRepository extends AbstractRepository implements ApiClientRepositoryInterface
{
    protected const ENTITY_CLASS = ApiClient::class;
}
