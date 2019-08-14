<?php
/**
 * FACTFinder_Campaigns
 *
 * @category Mage
 * @package FACTFinder_Campaigns
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */

/**
 * Class FACTFinder_Campaigns_Block_Feedback_Product
 *
 * @category Mage
 * @package FACTFinder_Campaigns
 * @author Flagbit Magento Team <magento@flagbit.de>
 * @copyright Copyright (c) 2016, Flagbit GmbH & Co. KG
 * @license https://opensource.org/licenses/MIT  The MIT License (MIT)
 * @link http://www.flagbit.de
 */
class FACTFinder_Campaigns_Block_Feedback_Product extends FACTFinder_Campaigns_Block_Feedback_Abstract
{

    protected $_handlerModel = 'factfinder_campaigns/handler_product';


    /**
     * Check is the campign can be shown on product page
     *
     * @return bool
     */
    protected function _canBeShown()
    {
        if (!Mage::registry('current_product')
            || !Mage::helper('factfinder_campaigns')->canShowCampaignsOnProduct())
        {
            return false;
        }
        return (bool) Mage::helper('factfinder')->isEnabled('campaigns');
    }


}