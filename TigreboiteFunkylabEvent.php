<?php

namespace Tigreboite\FunkylabBundle;

/**
 * Contains all events thrown in the FunkylabBundle
 */
class TigreboiteFunkylabEvent
{

    /**
     * @Event("Tigreboite\FunkylabBundle\Event\PageEvent")
     */
    const PAGE_CREATED = "funkylab.page_created";

    /**
     * @Event("Tigreboite\FunkylabBundle\Event\PageEvent")
     */
    const PAGE_UPDATED = "funkylab.page_updated";

    /**
     * @Event("Tigreboite\FunkylabBundle\Event\PageEvent")
     */
    const PAGE_DELETED = "funkylab.page_deleted";


}
