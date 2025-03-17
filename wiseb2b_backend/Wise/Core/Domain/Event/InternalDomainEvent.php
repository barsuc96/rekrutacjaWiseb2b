<?php

declare(strict_types=1);

namespace Wise\Core\Domain\Event;

/**
 * Internal domain event is used inside one domain
 * 
 * Internal domain event can reference domain objects: entities and value objects, schudn't referencje entities Id
 * 
 * UseCases:
 * * Internal domain event can synchronize changes between different agregates inside one domain
 * * Internal domain event can be subscribed in the app services for infrastructural tasks
 * 
 */
interface InternalDomainEvent extends DomainEvent
{
}
