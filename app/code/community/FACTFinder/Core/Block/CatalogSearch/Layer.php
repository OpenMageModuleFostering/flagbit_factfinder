<?php
/**
 * View.php
 *
 * @category Mage
 * @package FACTFinder_Core
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2017 Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 * @link http://www.flagbit.de
 */
class FACTFinder_Core_Block_CatalogSearch_Layer extends Mage_CatalogSearch_Block_Layer
{


    /**
     * Rewrite the block to prevent using default layer model
     *
     * @return FACTFinder_Core_Model_CatalogSearch_Layer|Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        if (Mage::helper('factfinder')->isEnabled()) {
            return Mage::getSingleton('factfinder/catalogSearch_layer');
        }

        return parent::getLayer();
    }


    /**
     * @return bool
     */
    public function canShowBlock()
    {
        if (Mage::helper('factfinder')->isEnabled()) {
            return (bool) $this->getLayer()->getProductCollection()->getSize();
        }

        return parent::canShowBlock();
    }


}