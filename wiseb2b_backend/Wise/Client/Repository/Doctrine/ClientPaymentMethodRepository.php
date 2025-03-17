<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethod;
use Wise\Client\Domain\ClientPaymentMethod\ClientPaymentMethodRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;


/**
 * @extends ServiceEntityRepository<ClientPaymentMethod>
 *
 * @method ClientPaymentMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientPaymentMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientPaymentMethod[]    findAll()
 * @method ClientPaymentMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientPaymentMethodRepository extends AbstractRepository implements ClientPaymentMethodRepositoryInterface
{
    protected const ENTITY_CLASS = ClientPaymentMethod::class;
}
