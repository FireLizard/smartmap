<?php

namespace Phoenix\Smartmap\Service;

/**
 * Service to handle data providers
 */
class DataProviderService
{
    /**
     * @var string
     */
    public static $GET_DATA_PROVIDER_SIGNAL = 'getSmartMapDataProvider';

    /**
     * Get all data providers by dispatching a signal
     * @return array $config An array filled with \Phoenix\Smartmap\Interface\DataProviderInterface (if any exists).
     */
    public static function getDataProviders($config)
    {
        $config['items'][] = array('', '');

        $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        $dispatcher->dispatch(__CLASS__, self::$GET_DATA_PROVIDER_SIGNAL, $config);

        return $config;
    }
}
