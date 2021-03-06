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
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_PostNL_Test_Unit_Model_DeliveryOptions_Product_Attribute_Source_ProductTypeTest
    extends TIG_PostNL_Test_Unit_Framework_TIG_Test_TestCase
{
    public function types()
    {
        /** @var TIG_PostNL_Helper_DeliveryOptions $deliveryOptionsHelper */
        $deliveryOptionsHelper = Mage::app()->getConfig()->getHelperClassName('postnl/deliveryOptions');

        return array(
            array($deliveryOptionsHelper::FOOD_TYPE_DRY_GROCERIES),
            array($deliveryOptionsHelper::FOOD_TYPE_COOL_PRODUCTS),
            array($deliveryOptionsHelper::IDCHECK_TYPE_AGE),
            array($deliveryOptionsHelper::IDCHECK_TYPE_BIRTHDAY),
            array($deliveryOptionsHelper::IDCHECK_TYPE_ID),
            array($deliveryOptionsHelper::EXTRA_AT_HOME_TYPE_REGULAR),
        );
    }

    /**
     * @dataProvider types
     */
    public function testHasAllTheOptions($type)
    {
        /** @var TIG_PostNL_Model_DeliveryOptions_Product_Attribute_Source_IdcheckType $model */
        $model = Mage::getModel('postnl_deliveryoptions/product_attribute_source_productType');

        $optionsGroups = $model->getAllOptions();

        foreach ($optionsGroups as $optionsGroup) {
            if (is_array($optionsGroup['value'])) {
                $options = $optionsGroup['value'];
            } else {
                $options = array($optionsGroup);
            }

            foreach ($options as $option) {
                if ($option['value'] === $type) {
                    $this->assertEquals($type, $option['value']);
                    return $this;
                }
            }
        }

        $this->fail('Option ' . $type . ' not found');
    }
}
