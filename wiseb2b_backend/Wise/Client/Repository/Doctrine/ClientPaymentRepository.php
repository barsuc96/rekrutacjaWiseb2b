<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Client\Domain\ClientPayment\ClientPayment;
use Wise\Client\Domain\ClientPayment\ClientPaymentRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<ClientPayment>
 *
 * @method ClientPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientPayment[]    findAll()
 * @method ClientPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientPaymentRepository extends AbstractRepository implements ClientPaymentRepositoryInterface
{
    protected const ENTITY_CLASS = ClientPayment::class;
}
