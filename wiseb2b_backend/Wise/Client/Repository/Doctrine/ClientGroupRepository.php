<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Wise\Client\Domain\ClientGroup\ClientGroup;
use Wise\Client\Domain\ClientGroup\ClientGroupRepositoryInterface;
use Wise\Client\Domain\ClientPriceList\ClientPriceList;
use Wise\Core\Entity\AbstractEntity;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<ClientGroup>
 *
 * @method ClientGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientGroup[]    findAll()
 * @method ClientGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientGroupRepository extends AbstractRepository implements ClientGroupRepositoryInterface
{
    protected const ENTITY_CLASS = ClientGroup::class;

    public function save(ClientGroup|AbstractEntity $entity, bool $flush = false): AbstractEntity
    {
        $priceLists = $entity->getPriceLists();
        $entity->setPriceLists(new ArrayCollection());

        $entity = parent::save($entity, $flush);

        /** @var ClientPriceList $priceList */
        foreach ($priceLists as $priceList) {
            $priceList->setClientGroup($entity);
        }
        $entity->setPriceLists($priceLists);

        return parent::save($entity, $flush);
    }
}
