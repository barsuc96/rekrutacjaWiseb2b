<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethod;
use Wise\Client\Domain\ClientDeliveryMethod\ClientDeliveryMethodRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;


/**
 * @extends ServiceEntityRepository<ClientDeliveryMethod>
 *
 * @method ClientDeliveryMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientDeliveryMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientDeliveryMethod[]    findAll()
 * @method ClientDeliveryMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDeliveryMethodRepository extends AbstractRepository implements ClientDeliveryMethodRepositoryInterface
{
    protected const ENTITY_CLASS = ClientDeliveryMethod::class;

    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry);
    }
}
