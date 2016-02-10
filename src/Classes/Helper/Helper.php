<?php

namespace Phoenix\Smartmap\Helper;

use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * Workaround to get Flexform by UID. Useful for AJAX-Calls.
 *
 * @see http://blog.wolf-whv.de/flexform-daten-eines-plugins-ajax-abfragen
 */
class Helper extends Repository
{
    /**
     * Find Flexform settings by UID.
     *
     * @param $uid
     *
     * @return array
     */
    public function findFlexformDataByUid($uid)
    {
        if (empty($uid)){
            return array();
        }

        $uid = (int) $uid;

        $query = $this->createQuery();
        $query->getQuerySettings()->setReturnRawQueryResult(true);
        $query->statement('SELECT pi_flexform from tt_content where list_type="smartmap_map" and uid = ' . $uid);
        $pages = $query->execute();

        $xml = simplexml_load_string($pages[0]['pi_flexform']);
        $flexformData = array();
        foreach ($xml->data->sheet->language->field as $field) {
            $flexformData[str_replace(
                'settings.flexform.',
                '',
                (string) $field->attributes()
            )] = (string) $field->value;
        }

        return $flexformData;
    }
}
