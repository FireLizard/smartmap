<?php

namespace Phoenix\Smartmap\Helper;

/**
 * Workaround to get Flexform by UID. Useful for AJAX-Calls.
 *
 * @see http://blog.wolf-whv.de/flexform-daten-eines-plugins-ajax-abfragen
 */
class Helper extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Find Flexform settings by UID.
     */
    public function findFlexformDataByUid($uid)
    {
        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(TRUE);
        $query->statement('SELECT pi_flexform from tt_content where list_type="smartmap_map" and uid = ' . $uid);
        $pages = $query->execute();

        $xml = simplexml_load_string($pages[0]['pi_flexform']);
        $flexformData = array();
        foreach ($xml->data->sheet->language->field as $field) {
        	$flexformData[str_replace('settings.flexform.','',(string)$field->attributes())] = (string)$field->value;
        }

        return $flexformData;
    }
}
