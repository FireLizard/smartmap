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
        $filterTemplate = NULL;

        if ($provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($this->settings['flexform']['dataProviderClass'])){

            $filterTemplate = $provider->getFilterTemplate();
        }

        $viewVars = array(
            'uid' => $contentObj->data['uid'],
            'filter' => $filterTemplate,
        );

        $this->view->assignMultiple($viewVars);
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
                'service' => '',
            ),
            'data' => array(),
        );

        if ($this->request->hasArgument('service') && is_callable(array($this->service, $args['service']))){

            $provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($this->settings['dataProviderClass']);
            $this->service->setDataProvider($provider);

            if ($this->request->hasArgument('serviceArguments')){

                $response['data'] = call_user_method($args['service'], $this->service, $args['serviceArguments']);
            }
            else {
                $response['data'] = $this->service->{$args['service']}();
            }

            $response['metadata']['service'] = $args['service'];
        }

        return json_encode($response);
    }

    /**
     * Initialize filter action
     *
     * @TODO Try to change XClass set in TypoScript for dynamic classnames.
     */
    public function initializeFilterAction()
    {
    }

    /**
     * Filter action.
     *
     * @param \Phoenix\Smartmap\Domain\Model\AbstractFilter $filter
     */
    public function filterAction(\Phoenix\Smartmap\Domain\Model\AbstractFilter $filter)
    {
        $this->settings = array_merge($this->settings, $this->helper->findFlexformDataByUid($this->request->getArguments()['uid']));

        $response = array(
            'metadata' => array(
                'settings' => $this->settings,
                'service' => '',
            ),
            'data' => array(),
        );

        $provider = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance($this->settings['dataProviderClass']);
        $this->service->setDataProvider($provider);
        $response['data'] = $this->service->getFilteredMarkers($filter);

        return json_encode($response);
    }
}
