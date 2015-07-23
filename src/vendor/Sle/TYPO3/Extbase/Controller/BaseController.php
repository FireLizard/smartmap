<?php

namespace Sle\TYPO3\Extbase\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;

/**
 * TYPO3 Backend PluginPreview
 *
 * @author Steve Lenz <kontakt@steve-lenz.de>
 * @copyright (c) 2014, Steve Lenz
 * @version 1.2.0
 * @package TYPO3 6.2.x
 */
class BaseController extends ActionController
{
    /**
     * cObject
     *
     * @var object
     */
    protected $cObj = null;

    /**
     * Extensionkey
     *
     * @var string
     */
    protected $extensionKey = null;

    /**
     * The uid of the current content element
     * @var int
     */
    protected $uid = null;

    /**
     * Initializer
     */
    public function initializeAction()
    {
        $this->extensionKey = $this->request->getControllerExtensionKey();
        $this->cObj         = $this->configurationManager->getContentObject();
        $this->uid          = $this->cObj->data['uid'];
    }

    /**
     *
     * @param string $className
     * @return instannce
     */
    public function makeInstance($className)
    {
        $extbaseObjectManager = GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
        return $extbaseObjectManager->get($className);
    }

    /**
     * Property mapping helper
     *
     * @param string $name
     * @param array $properties
     */
    protected function propertyMapper($name, array $properties)
    {
        if ($this->request->hasArgument($name)) {
            $propertyMappingConfiguration = $this->arguments[$name]->getPropertyMappingConfiguration();
            foreach ($properties as $property) {
                $propertyMappingConfiguration->allowProperties($property);
            }
            $propertyMappingConfiguration->setTypeConverterOption('TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter',
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                TRUE);
        }
    }

    /**
     * Fügt HTML-Code zum HTML-head hinzu
     * @param string $html
     */
    protected function addToHead($html)
    {
        if (!in_array($html, $this->response->getAdditionalHeaderData())) {
            $this->response->addAdditionalHeaderData($html);
        }
    }

    /**
     * Fügt JavaScripts zum HTML-head hinzu
     * Die JS-Dateien müssen sich in Resources/Public/Scripts befinden.
     * ".js" wird automatisch angefügt.
     *
     * @param array $scripts
     */
    protected function addJavascripts(array $scripts)
    {
        foreach ($scripts as $script) {
            $path = str_replace('EXT:',
                ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()),
                $script);
            $this->addToHead('<script type="text/javascript" src="'.$path.'"></script>');
        }
    }

    /**
     * Fügt Stylesheets zum HTML-head hinzu.
     * Die CSS-Dateien müssen sich in Resources/Public/Styles befinden.
     * ".css" wird automatisch angefügt.
     *
     * @param array $stylesheets
     */
    protected function addStylesheets(array $stylesheets)
    {
        foreach ($stylesheets as $stylesheet) {
            $stylesheet = str_replace('EXT:',
                ExtensionManagementUtility::siteRelPath($this->request->getControllerExtensionKey()),
                $stylesheet);
            $this->addToHead('<link rel="stylesheet" href="'.$stylesheet.'" />');
        }
    }
}
