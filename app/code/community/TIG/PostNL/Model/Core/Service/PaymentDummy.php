<?php
/**
 *                  ___________       __            __
 *                  \__    ___/____ _/  |_ _____   |  |
 *                    |    |  /  _ \\   __\\__  \  |  |
 *                    |    | |  |_| ||  |   / __ \_|  |__
 *                    |____|  \____/ |__|  (____  /|____/
 *                                              \/
 *          ___          __                                   __
 *         |   |  ____ _/  |_   ____ _______   ____    ____ _/  |_
 *         |   | /    \\   __\_/ __ \\_  __ \ /    \ _/ __ \\   __\
 *         |   ||   |  \|  |  \  ___/ |  | \/|   |  \\  ___/ |  |
 *         |___||___|  /|__|   \_____>|__|   |___|  / \_____>|__|
 *                  \/                           \/
 *                  ________
 *                 /  _____/_______   ____   __ __ ______
 *                /   \  ___\_  __ \ /  _ \ |  |  \\____ \
 *                \    \_\  \|  | \/|  |_| ||  |  /|  |_| |
 *                 \______  /|__|    \____/ |____/ |   __/
 *                        \/                       |__|
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) 2015 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_PostNL_Model_Core_Service_PaymentDummy extends Mage_Sales_Model_Order_Payment
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'postnl_dummy_payment';

    /**
     * @return bool
     */
    public function canCapture()
    {
        return false;
    }

    /**
     * @param Varien_Object $document
     *
     * @return bool
     */
    public function canVoid(Varien_Object $document)
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canRefund()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canRefundPartialPerInvoice()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canCapturePartial()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canReviewPayment()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function canFetchTransactionInfo()
    {
        return false;
    }
}
