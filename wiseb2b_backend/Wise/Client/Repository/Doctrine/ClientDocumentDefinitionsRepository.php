<?php

namespace Wise\Client\Repository\Doctrine;

use Wise\Client\Domain\ClientDocumentDefinition\ClientDocumentDefinition;
use Wise\Core\Repository\AbstractRepository;

class ClientDocumentDefinitionsRepository extends AbstractRepository
{
    protected const ENTITY_CLASS = ClientDocumentDefinition::class;
}