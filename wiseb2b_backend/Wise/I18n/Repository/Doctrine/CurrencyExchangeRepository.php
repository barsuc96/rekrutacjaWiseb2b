<?php

declare(strict_types=1);

namespace Wise\I18n\Repository\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Core\Repository\AbstractRepository;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchange;
use Wise\I18n\Domain\CurrencyExchange\CurrencyExchangeRepositoryInterface;

/**
 * @extends ServiceEntityRepository<CurrencyExchange>
 *
 * @method CurrencyExchange|null find($id, $lockMode = null, $lockVersion = null)
 * @method CurrencyExchange|null findOneBy(array $criteria, array $orderBy = null)
 * @method CurrencyExchange[]    findAll()
 * @method CurrencyExchange[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyExchangeRepository extends AbstractRepository implements CurrencyExchangeRepositoryInterface
{
    protected const ENTITY_CLASS = CurrencyExchange::class;
}
