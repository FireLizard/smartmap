<?php

namespace Phoenix\Smartmap\Controller;

use Phoenix\Smartmap\Domain\Model\AbstractQuery;
use Phoenix\Smartmap\Provider\DataProviderInterface;
use Phoenix\Smartmap\Provider\FilterProviderInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\Container\Exception\UnknownObjectException;

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
        if ( ! $this->settings){
            $GLOBALS['TSFE']->pageNotFoundAndExit('Settings not available');
        }

        $cObj = $this->configurationManager->getContentObject();
        $filterTemplate = null;

        /** @var FilterProviderInterface $provider */
        $provider = $this->objectManager->get($this->settings['flexform']['filterProviderClass']);
        if ($provider){

            $filterTemplate = $provider->getFilterTemplate();
            $filterTemplate->setControllerContext($this->getControllerContext());
            $filterTemplate = trim($filterTemplate->render());
        }

        $this->view->assignMultiple(array(
            'uid' => $cObj->data['uid'],
            'filterTemplate' => $filterTemplate,
        ));
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
            $provider = GeneralUtility::makeInstance($this->settings['dataProviderClass'], $this->settings['persistence.storagePid']);
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
     */
    public function initializeFilterAction()
    {
        $this->settings = array_merge(
            $this->settings,
            $this->helper->findFlexformDataByUid($this->request->getArguments()['uid'])
        );

        if ($this->arguments->hasArgument('query')){

            try {
                /** @var FilterProviderInterface $provider */
                $provider = $this->objectManager->get($this->settings['filterProviderClass']);
            }
            catch (UnknownObjectException $e){
                $GLOBALS['TSFE']->pageNotFoundAndExit('filterProviderClass not available');
            }

            $this->arguments->getArgument('query')
                ->setRequired(false)
                ->setDataType($provider->getQueryClassname())
            ;
        }
    }

    /**
     * Filter action.
     *
     * @param AbstractQuery $query
     *
     * @return string
     */
    public function filterAction(AbstractQuery $query = null)
    {
        $response = array(
            'metadata' => array(
                'settings' => $this->settings,
                'service' => '',
            ),
            'data'     => array(),
        );

        if ($query != null) {

            $provider = $this->objectManager->get($this->settings['dataProviderClass'], $this->settings['persistence.storagePid']);
            if ($provider instanceof DataProviderInterface) {

                $response['data'] = $this->service->setDataProvider($provider)->getFilteredMarkers($query);
            }
        }

        return json_encode($response);
    }
}
