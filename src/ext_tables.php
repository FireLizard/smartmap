<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Map',
	'Map'
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Smart Map');

/**
* Register FlexForms
*/
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));
$pluginNames   = array(
	'map',
);

foreach ($pluginNames as $pluginName) {

	$pluginSignature = $extensionName .'_'. $pluginName;
	$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
		$pluginSignature,
		'FILE:EXT:'. $_EXTKEY .'/Configuration/FlexForms/'. $pluginName .'.xml'
	);
}
