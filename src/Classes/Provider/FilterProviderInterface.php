<?php

namespace Phoenix\Smartmap\Provider;

use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

/**
 * Interface FilterProviderInterface
 * @package Phoenix\Smartmap\Provider
 */
interface FilterProviderInterface
{
    /**
     * Get classname of query object
     *
     * @return string
     */
    public function getQueryClassname();

    /**
     * Returns a rendered filter form
     *
     * @return ViewInterface
     */
    public function getFilterTemplate();
}
