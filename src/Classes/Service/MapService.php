<?php

namespace Phoenix\Smartmap\Service;

use Phoenix\Smartmap\Provider\DataProviderInterface;

/**
 * Service to handle map actions
 */
class MapService
{
    /**
     * @var DataProviderInterface
     */
    protected $dataProvider = null;

    /**
     * Set DataProvider
     *
     * @param DataProviderInterface $provider A DataProvider, e.g. configured by FlexForm.
     *
     * @return MapService
     */
    public function setDataProvider(DataProviderInterface $provider)
    {
        $this->dataProvider = $provider;

        return $this;
    }

    /**
     * Get all markers from data provider. Marker means a structure of coords, popup and additional metadata.
     *
     * @return array
     */
    public function getMarkers()
    {
        $markers['data'] = (array) $this->dataProvider->getDataByQuery(null);
        $markers['coords'] = (array) $this->dataProvider->getAllCoords();
        $markers['popup'] = (array) $this->dataProvider->getAllPopupsContent();

        return $markers;
    }

    /**
     * Get markers filtered by a filter object. The filter object is defined by data provider.
     *
     * @param  mixed $query The query object
     *
     * @return array
     */
    public function getFilteredMarkers($query)
    {
        $markers['data'] = (array) $this->dataProvider->getDataByQuery($query);
        $markers['coords'] = (array) $this->dataProvider->getCoordsByQuery($query);
        /** @TODO Do not load all PopUps ;) */
        $markers['popup'] = (array) $this->dataProvider->getAllPopupsContent();

        return $markers;
    }
}
