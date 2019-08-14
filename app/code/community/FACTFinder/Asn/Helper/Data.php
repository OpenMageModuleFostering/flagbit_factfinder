<?php
/**
 * FACTFinder_Asn
 *
 * @category Mage
 * @package FACTFinder_Asn
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */

/**
 * Class FACTFinder_Asn_Helper_Data
 *
 * @category Mage
 * @package FACTFinder_Asn
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */
class FACTFinder_Asn_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * A sequence that is unlikely to occur in an URL
     */
    const QUERY_PLACEHOLDER = 'XWPZVYTAQOJ';


    /**
     * Parse url and return array of parameters
     *
     * @param string $url
     *
     * @return array
     */
    public function getQueryParams($url)
    {
        $queryParams = array();

        //conserve url encoded spaces, since parse_str replaces them with underscores
        $url = str_replace('%20', self::QUERY_PLACEHOLDER, $url);

        $parseUrl = parse_url($url);
        if (isset($parseUrl['query'])) {
             $queryParams = $this->parseStr($parseUrl['query']);
        }

        // recover spaces
        // we use not encoded values since they will be encoded with Mage::getUrl()
        $result = array();
        foreach ($queryParams as $key => $value) {
            $key = str_replace(self::QUERY_PLACEHOLDER, ' ', $key);
            $value = str_replace(self::QUERY_PLACEHOLDER, ' ', $value);
            $result[$key] = $value;
        }

        return $result;
    }


    /**
     * Check is catalog navigation replacement is enabled
     *
     * @return bool
     */
    public function isCatalogNavigation()
    {
        return (bool) Mage::app()->getStore()->getConfig('factfinder/modules/catalog_navigation');
    }


    /**
     * Remove category filter params if they are the save as the current category ones
     *
     * On catalog navigation if we use all the params from ff we have unnecessary ugly params
     * which we don't need. This function removes them
     *
     * @param string $url
     *
     * @return mixed
     */
    public function removeCategoryParams($url)
    {
        $categoryPath = Mage::getSingleton('factfinder_asn/handler_search')->getCurrentFactFinderCategoryPath();
        $query = http_build_query($categoryPath);
        $query = str_replace('+', '%20', $query);
        $url = str_replace($query, '', $url);
        //remove redundant &
        $url = str_replace(array('?&', '&&'), array('?', '&'), $url);

        return $url;
    }


    /**
     * Does practically the same as parse_str
     * but does NOT underscore parameter names
     *
     * @param string $string
     *
     * @return array
     *
     */
    public function parseStr($string)
    {
        $result = array();
        $query = trim($string, '?&');
        $query = explode('&', $query);

        foreach ($query as $item) {
            $item = explode('=', $item);
            $result[urldecode(array_shift($item))] = urldecode(array_shift($item));
        }

        return $result;
    }


}