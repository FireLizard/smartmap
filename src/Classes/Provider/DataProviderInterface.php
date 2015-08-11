<?php

namespace Phoenix\Smartmap\Provider;

interface DataProviderInterface
{
    public function getData();

    /**
     * Get all coords.
     *
     * @return array An array like: array([lat, lon], [lat, lon], ...).
     */
    public function getAllCoords();

    /**
     * Get content of all popups.
     *
     * @return array An array like: array(popup1, popup2, ...).
     */
    public function getAllPopupsContent();

    /**
     * Returns a rendered filter formular
     *
     * @return string
     */
    public function getFilterTemplate();

    /**
     * Get coords by a query object
     * @param  mixed $query The query object.
     * @return array An array like: array([lat, lon], [lat, lon], ...).
     */
    public function getCoordsByQuery($query);
}
