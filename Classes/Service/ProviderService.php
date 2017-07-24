<?php

namespace FireLizard\Smartmap\Service;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service to handle data providers.
 */
class ProviderService
{
    public static $getDataProvider_SIGNAL = 'getSmartMapDataProvider';
    public static $getFilterProvider_SIGNAL = 'getSmartMapFilterProvider';

    /**
     * Get all data providers by dispatching a signal.
     *
     * @param $config
     *
     * @return array $config An array filled with \FireLizard\Smartmap\Provider\DataProviderInterface (if any exists).
     */
    public static function getDataProviders($config)
    {
        $config['items'][] = array('', '');

        $dispatcher = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        $dispatcher->dispatch(__CLASS__, self::$getDataProvider_SIGNAL, $config);

        return $config;
    }

    /**
     * Get all data providers by dispatching a signal.
     *
     * @param $config
     *
     * @return array $config An array filled with \FireLizard\Smartmap\Provider\FilterProviderInterface (if any exists).
     */
    public static function getFilterProviders($config)
    {
        $config['items'][] = array('', '');

        $dispatcher = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
        $dispatcher->dispatch(__CLASS__, self::$getFilterProvider_SIGNAL, $config);

        return $config;
    }
}
