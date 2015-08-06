<?php

namespace Phoenix\Smartmap\Service;

use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use Phoenix\Smartmap\Provider\DataProviderInterface;

/**
 * Service to handle map actions
 */
class MapService
{
    /**
     * @var \Phoenix\Smartmap\Provider\DataProviderInterface
     */
    protected $dataProvider = null;

    /**
     * Set DataProvider
     *
     * @param \Phoenix\Smartmap\Provider\DataProviderInterface $provider A DataProvider, e.g. configured by FlexForm.
     * @return MapService
     */
    public function setDataProvider(\Phoenix\Smartmap\Provider\DataProviderInterface $provider)
    {
        $this->dataProvider = $provider;

        return $this;
    }

    /**
     * Get all coords from data provider
     *
     * @return array
     */
    public function getCoords()
    {
        return (array) $this->dataProvider->getAllCoords();
    }
}
