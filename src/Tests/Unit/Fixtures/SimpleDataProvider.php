<?php

namespace Phoenix\Smartmap\Tests\Unit\Fixtures;

/**
 * Simple data provider
 */
class SimpleDataProvider extends  \Phoenix\Smartmap\Provider\DataProviderInterface
{
    /**
     * @{inherit}
     */
    public function getData()
    {
        return array('SimpleDataProvider' => __CLASS__);
    }
}
