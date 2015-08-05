<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'Phoenix.'.$_EXTKEY,
    'Map',
    array(
        'Map' => 'show, ajax',

    ),
    // non-cacheable actions
    array(

    )
);
