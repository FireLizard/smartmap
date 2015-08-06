<?php

namespace Phoenix\Smartmap\Controller;

/**
 * Map Controller.
 */
class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * MapService
     *
     * @var \Phoenix\Smartmap\Service\MapService
     * @inject
     */
    protected $service = NULL;

    /**
     * Helper
     *
     * @var \Phoenix\Smartmap\Helper\Helper
     * @inject
     */
    protected $helper = NULL;

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
        $contentObj = $this->configurationManager->getContentObject();

        $this->view->assign('uid', $contentObj->data['uid']);
    }

    /**
     * AJAX handler
     */
    public function ajaxAction()
    {
        $args = $this->request->getArguments();
        $this->settings = array_merge($this->settings, $this->helper->findFlexformDataByUid($this->request->getArguments()['uid']));

        $response = array(
            'metadata' => array(
                'settings' => $this->settings,
            ),
            'data' => array(),
        );

        if ($this->request->hasArgument('service') && is_callable(array($this->service, $args['service']))){

            $provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($this->settings['dataProviderClass']);
            $this->service->setDataProvider($provider);
            $response['data'] = $this->service->{$args['service']}();
        }

        return json_encode($response);
    }
}
