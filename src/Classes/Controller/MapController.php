<?php

namespace Phoenix\Smartmap\Controller;

/**
 * Map Controller.
 */
class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * initialize action show.
     */
    public function initializeShowAction()
    {
    }

    /**
     * action show.
     */
    public function showAction()
    {
    }

    public function ajaxAction()
    {
        return json_encode($this->request->getArguments());
    }
}
