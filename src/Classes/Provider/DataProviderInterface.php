<?php

namespace Phoenix\Smartmap\Provider;

interface DataProviderInterface
{
    public function getData();

    /**
     * Get all coords as array like: array([lat, lon], [lat, lon], ...).
     *
     * @return array
     */
    public function getAllCoords();

    /**
     * Get content of all popups as array like: array(popup1, popup2, ...).
     *
     * @return array
     */
    public function getAllPopupsContent();

    /**
     * Returns a rendered filter formular
     *
     * @return string
     */
    public function getFilterTemplate();
}
