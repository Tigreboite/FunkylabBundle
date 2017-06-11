<?php

namespace Tigreboite\FunkylabBundle;

/**
 * Contains all events thrown in the FunkylabBundle.
 */
class TigreboiteFunkylabEvent
{
    /**
     * @Event("Tigreboite\FunkylabBundle\Event\EntityEvent")
     */
    const ENTITY_CREATED = 'funkylab.entity_created';

    /**
     * @Event("Tigreboite\FunkylabBundle\Event\EntityEvent")
     */
    const ENTITY_UPDATED = 'funkylab.entity_updated';

    /**
     * @Event("Tigreboite\FunkylabBundle\Event\EntityEvent")
     */
    const ENTITY_DELETED = 'funkylab.entity_deleted';
}
