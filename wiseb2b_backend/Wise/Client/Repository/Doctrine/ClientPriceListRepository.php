<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Client\Domain\ClientPriceList\ClientPriceList;
use Wise\Client\Repository\ClientPriceListRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<ClientPriceList>
 *
 * @method ClientPriceList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientPriceList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientPriceList[]    findAll()
 * @method ClientPriceList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientPriceListRepository extends AbstractRepository implements ClientPriceListRepositoryInterface
{
    protected const ENTITY_CLASS = ClientPriceList::class;
}
