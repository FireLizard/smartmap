<?php

namespace Phoenix\Tests\Unit\Service;

use Phoenix\Smartmap\Service\DataProviderService;

/**
 * Test case for class Phoenix\Smartmap\Service\DataProviderService.
 */
class DataProviderServiceTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
    /**
     * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
     */
    protected $dispatcher = null;

    protected function setUp()
    {
        $this->dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
    }

    protected function tearDown()
    {
        unset($this->dispatcher);
    }

    /**
     * @test
     */
    public function getDataProviderTest()
    {
        $expect = array(
            'items' => array(
                array('', ''),
                array('TestProvider', 'Phoenix\\Smartmap\\Tests\\Unit\\Fixtures\\SimpleDataProvider'),
            ),
        );

        $this->dispatcher->connect(
            'Phoenix\\Smartmap\\Service\\DataProviderService',
            DataProviderService::$GET_DATA_PROVIDER_SIGNAL,
            function (&$data, $signal) use (&$expect) {
                $data[] = $expect['items'][1];
            },
            null
        );

        $items = array();
        $config['items'] = &$items;
        $providers = DataProviderService::getDataProviders($config);

        $this->assertEquals($expect, $providers);
    }
}
