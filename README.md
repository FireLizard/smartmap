smartmap
========
A really smart TYPO3 CMS Map Extension for pretty interaction.

General Information
-------------------
- Use of CSS-Loader by http://codepen.io/martinvd/ from http://cssload.net
- JavaScript-libraries for interactive maps out of the box:  
    * [leaflet](http://leafletjs.com/)

Installation
------------
1. Install via [composer](https://getcomposer.org/): `composer require typo3-ter/smartmap`
2. Activate via *Extension Manager* 
3. Include static templates
4. Include assets (found at *EXT:smartmap/Resources/Public/Css* and *EXT:smartmap/Resources/Public/Js*)
5. Include assets of map library (**which are not included in this extension!**)

Provide your payload by using Signal-Slot-Pattern
-------------------------------------------------
To **provide your data** simple follow these steps:

1. Implement the Interface `FireLizard\Smartmap\Provider\DataProviderInterface`, e.g. as `My\ExtensionName\Provider\MyDataProvider`.
2. Register a slot for the signal inside your ext_localconf.php:
```php
// signal-slot to connect with EXT:smartmap
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
    \FireLizard\Smartmap\Service\ProviderService::class,
    \FireLizard\Smartmap\Service\ProviderService::$getDataProvider_SIGNAL,
    function(&$data, $signal) {
        $data[] = array('MyDataProvider', 'My\\ExtensionName\\Provider\\MyDataProvider');
    },
    NULL
);
```

To **use a filterform** follow these steps:

1. Implement the Interface `FireLizard\Smartmap\Provider\FilterProviderInterface`, e.g. as `My\ExtensionName\Provider\MyFilterProvider`.
2. Register a slot for the signal inside your ext_localconf.php:
```php
// signal-slot to connect with EXT:smartmap
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
    \FireLizard\Smartmap\Service\ProviderService::class,
    \FireLizard\Smartmap\Service\ProviderService::$getFilterProvider_SIGNAL,
    function(&$data, $signal) {
        $data[] = array('MyFilterProvider', 'My\\ExtensionName\\Provider\\MyFilterProvider');
    },
    NULL
);
```
