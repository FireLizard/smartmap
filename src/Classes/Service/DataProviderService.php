<?php

namespace Phoenix\Smartmap\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service to handle data providers.
 */
class DataProviderService
{
    /**
     * @var string
     */
    public static $GET_DATA_PROVIDER_SIGNAL = 'getSmartMapDataProvider';

    /**
     * Get all data providers by dispatching a signal.
     *
     * @param $config
     *
     * @return array $config An array filled with \Phoenix\Smartmap\Provider\DataProviderInterface (if any exists).
     */
    public static function getDataProviders($config)
    {
        $config['items'][] = array('', '');

        $dispatcher = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        $dispatcher->dispatch(__CLASS__, self::$GET_DATA_PROVIDER_SIGNAL, $config);

        return $config;
    }
}
