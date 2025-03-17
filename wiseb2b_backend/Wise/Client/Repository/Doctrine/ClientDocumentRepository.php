<?php

declare(strict_types=1);

namespace Wise\Client\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Client\Domain\ClientDocument\ClientDocument;
use Wise\Client\Domain\ClientDocument\ClientDocumentRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

/**
 * @extends ServiceEntityRepository<ClientDocument>
 *
 * @method ClientDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientDocument[]    findAll()
 * @method ClientDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientDocumentRepository extends AbstractRepository implements ClientDocumentRepositoryInterface
{
    protected const ENTITY_CLASS = ClientDocument::class;
}
