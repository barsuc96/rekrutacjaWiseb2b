<?php

declare(strict_types=1);

namespace Wise\Core\RepositoryInterface\Cart;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Wise\Cart\Entity\Cart;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface CartRepositoryInterface
{
    public function add(Cart $entity, bool $flush = false): void;

    public function remove(Cart $entity, bool $flush = false): void;
}