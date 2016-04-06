smartmap
========
A really smart TYPO3 CMS Map Extension for pretty interaction.

##
Use of CSS-Loader by http://codepen.io/martinvd/ from http://cssload.net

Signal-Slot-Pattern
-------------------
To provide data simple follow these steps:

1. Implement the Interface `Phoenix\Smartmap\Provider\DataProviderInterface`, e.g. as `My\ExtensionName\Provider\MyDataProvider`.
2. Register a slot for the signal inside your ext_localconf.php:
```php
// signal-slot to connect with EXT:smartmap
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher')->connect(
    'Phoenix\\Smartmap\\Service\\DataProviderService',
    \Phoenix\Smartmap\Service\DataProviderService::$GET_DATA_PROVIDER_SIGNAL,
    function(&$data, $signal) {
        $data[] = array('MyDataProvider', 'My\\ExtensionName\\Provider\\MyDataProvider');
    },
    NULL
);
```

Queryobject for filterform
--------------------------
To provide a queryobject for a filterform simple set the FQDN of your Query in the constant editor.
Under "XClass" -> "Classname of queryobject for filterform" set "My\ExtensionName\Domain\Model\MyDataQuery" or
`plugin.tx_smartmap.objects.Phoenix\Smartmap\Domain\Model\AbstractFilter.className = My\ExtensionName\Domain\Model\MyDataQuery`
