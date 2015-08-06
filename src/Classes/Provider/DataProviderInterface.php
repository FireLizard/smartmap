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
}
