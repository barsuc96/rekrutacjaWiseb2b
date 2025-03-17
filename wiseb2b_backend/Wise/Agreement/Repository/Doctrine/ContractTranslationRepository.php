<?php

namespace Wise\Agreement\Repository\Doctrine;

use Wise\Agreement\Repository\Doctrine\ContractTranslation\ContractTranslation;
use Wise\Agreement\Repository\Doctrine\ContractTranslation\ContractTranslationRepositoryInterface;
use Wise\Core\Repository\AbstractRepository;

class ContractTranslationRepository extends AbstractRepository implements ContractTranslationRepositoryInterface
{
    protected const ENTITY_CLASS = ContractTranslation::class;
}
