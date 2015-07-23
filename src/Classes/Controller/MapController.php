<?php

namespace Phoenix\Smartmap\Controller;

/**
 * Map Controller
 */
class MapController extends \Sle\TYPO3\Extbase\Controller\BaseController
{
    /**
     * initialize action show
     *
     * @return void
     */
    public function initializeShowAction() {

        $this->addStylesheets((array) $this->settings['assets']['leaflet']['css']);
        $this->addJavascripts((array) $this->settings['assets']['leaflet']['js']);
    }

    /**
     * action show
     *
     * @return void
     */
    public function showAction() {

    }
}
