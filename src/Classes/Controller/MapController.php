<?php

namespace Phoenix\Smartmap\Controller;

use Phoenix\Smartmap\Domain\Model\AbstractFilter;
use Phoenix\Smartmap\Provider\DataProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Map Controller.
 */
class MapController extends ActionController
{
    /**
     * MapService
     *
     * @var \Phoenix\Smartmap\Service\MapService
     * @inject
     */
    protected $service = null;

    /**
     * Helper
     *
     * @var \Phoenix\Smartmap\Helper\Helper
     * @inject
     */
    protected $helper = null;

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
        $filterTemplate = null;

        if ($provider = GeneralUtility::makeInstance(
            $this->settings['flexform']['dataProviderClass']
        )
        ) {

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
        $this->settings = array_merge(
            $this->settings,
            $this->helper->findFlexformDataByUid($this->request->getArguments()['uid'])
        );

        $response = array(
            'metadata' => array(
                'settings' => $this->settings,
                'service' => '',
            ),
            'data'     => array(),
        );

        if ($this->request->hasArgument('service') &&
            is_callable(
                array(
                    $this->service,
                    $args['service'],
                )
            )
        ) {
            $provider = GeneralUtility::makeInstance($this->settings['dataProviderClass']);
            if ($provider instanceof DataProviderInterface) {

                $this->service->setDataProvider($provider);

                if ($this->request->hasArgument('serviceArguments')) {

                    $response['data'] = call_user_func([$this->service, $args['service']], $args['serviceArguments']);
                }
                else {
                    $response['data'] = $this->service->{$args['service']}();
                }

                $response['metadata']['service'] = $args['service'];
            }
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
        $this->arguments->getArgument('filter')->setRequired(false);
    }

    /**
     * Filter action.
     *
     * @param AbstractFilter $filter
     *
     * @return string
     */
    public function filterAction(AbstractFilter $filter)
    {
        $this->settings = array_merge(
            $this->settings,
            $this->helper->findFlexformDataByUid($this->request->getArguments()['uid'])
        );

        $response = array(
            'metadata' => array(
                'settings' => $this->settings,
                'service' => '',
            ),
            'data'     => array(),
        );

        if ($filter != null) {

            $provider = GeneralUtility::makeInstance($this->settings['dataProviderClass']);
            if ($provider instanceof DataProviderInterface) {

                $this->service->setDataProvider($provider);
                $response['data'] = $this->service->getFilteredMarkers($filter);
            }
        }

        return json_encode($response);
    }
}
