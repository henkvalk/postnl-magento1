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
 * @copyright   Copyright (c) 2014 Total Internet Group B.V. (http://www.tig.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_PostNL_Helper_Carrier extends TIG_PostNL_Helper_Data
{
    /**
     * Shipping carrier code used by PostNL.
     */
    const POSTNL_CARRIER = 'postnl';

    /**
     * PostNL shipping methods.
     */
    const POSTNL_FLATRATE_METHOD  = 'flatrate';
    const POSTNL_TABLERATE_METHOD = 'tablerate';
    const POSTNL_MATRIX_METHOD    = 'matrixrate';

    /**
     * Localised track and trace base URL's.
     */
    const POSTNL_TRACK_AND_TRACE_NL_BASE_URL_XPATH  = 'postnl/cif/track_and_trace_nl_base_url';
    const POSTNL_TRACK_AND_TRACE_GB_BASE_URL_XPATH  = 'postnl/cif/track_and_trace_gb_base_url';
    const POSTNL_TRACK_AND_TRACE_DE_BASE_URL_XPATH  = 'postnl/cif/track_and_trace_de_base_url';
    const POSTNL_TRACK_AND_TRACE_FR_BASE_URL_XPATH  = 'postnl/cif/track_and_trace_fr_base_url';
    const POSTNL_TRACK_AND_TRACE_INT_BASE_URL_XPATH = 'postnl/cif/track_and_trace_int_base_url';

    /**
     * XML path to rate type setting.
     */
    const XPATH_RATE_TYPE = 'carriers/postnl/rate_type';

    /**
     * Xpath to the 'postnl_shipping_methods' setting.
     */
    const XPATH_POSTNL_SHIPPING_METHODS = 'postnl/advanced/postnl_shipping_methods';

    /**
     * Array of possible PostNL shipping methods.
     *
     * @var array
     */
    protected $_postnlShippingMethods;

    /**
     * Array of shipping methods that have already been checked for whether they're PostNL.
     *
     * @var array
     */
    protected $_matchedMethods = array();

    /**
     * Gets an array of possible PostNL shipping methods.
     *
     * @return array
     */
    public function getPostnlShippingMethods()
    {
        if ($this->_postnlShippingMethods) {
            return $this->_postnlShippingMethods;
        }

        $cache = $this->getCache();
        if ($cache && $cache->hasPostnlShippingMethods()) {
            $shippingMethods = $cache->getPostnlShippingMethods();

            $this->setPostnlShippingMethods($shippingMethods);
            return $shippingMethods;
        }

        $shippingMethods = Mage::getStoreConfig(self::XPATH_POSTNL_SHIPPING_METHODS, Mage::app()->getStore()->getId());
        $shippingMethods = explode(',', $shippingMethods);

        if ($cache) {
            $cache->setPostnlShippingMethods($shippingMethods);
        }

        $this->setPostnlShippingMethods($shippingMethods);
        return $shippingMethods;
    }

    /**
     * @param array $postnlShippingMethods
     *
     * @return $this
     */
    public function setPostnlShippingMethods($postnlShippingMethods)
    {
        $this->_postnlShippingMethods = $postnlShippingMethods;

        return $this;
    }

    /**
     * @return array
     */
    public function getMatchedMethods()
    {
        $matchedMethods = $this->_matchedMethods;
        if (!empty($matchedMethods)) {
            return $matchedMethods;
        }

        $cache = $this->getCache();
        if ($cache && $cache->hasMatchedPostnlShippingMethods()) {
            $this->setMatchedMethods(
                $cache->getMatchedPostnlShippingMethods()
            );
        }

        return $this->_matchedMethods;
    }

    /**
     * @param array $matchedMethods
     *
     * @return $this
     */
    public function setMatchedMethods($matchedMethods)
    {
        $this->_matchedMethods = $matchedMethods;

        $cache = $this->getCache();
        if ($cache) {
            $cache->setMatchedPostnlShippingMethods($matchedMethods);
        }

        return $this;
    }

    /**
     * Adds a matched method to the matched methods array.
     *
     * @param string  $method
     * @param boolean $value
     *
     * @return $this
     */
    public function addMatchedMethod($method, $value)
    {
        $matchedMethods = $this->getMatchedMethods();
        $matchedMethods[$method] = $value;

        $this->setMatchedMethods($matchedMethods);
        return $this;
    }

    /**
     * Alias for getCurrentPostnlShippingMethod()
     *
     * @return string
     *
     * @see TIG_PostNL_Helper_Carrier::getCurrentPostnlShippingMethod()
     *
     * @deprecated
     */
    public function getPostnlShippingMethod()
    {
        return $this->getCurrentPostnlShippingMethod();
    }

    /**
     * Returns the PostNL shipping method
     *
     * @param null $storeId
     *
     * @throws TIG_PostNL_Exception
     * @return string
     */
    public function getCurrentPostnlShippingMethod($storeId = null)
    {
        if (Mage::registry('current_postnl_shipping_method') !== null) {
            return Mage::registry('current_postnl_shipping_method');
        }

        if (is_null($storeId)) {
            $storeId = Mage::app()->getStore()->getId();
        }

        $rateType = Mage::getStoreConfig(self::XPATH_RATE_TYPE, $storeId);

        $carrier = self::POSTNL_CARRIER;
        switch ($rateType) {
            case 'flat':
                $shippingMethod = $carrier . '_' . self::POSTNL_FLATRATE_METHOD;
                break;
            case 'table':
                $shippingMethod = $carrier . '_' . self::POSTNL_TABLERATE_METHOD;
                break;
            case 'matrix':
                $shippingMethod = $carrier . '_' . self::POSTNL_MATRIX_METHOD;
                break;
            default:
                throw new TIG_PostNL_Exception(
                    $this->__('Invalid rate type requested: %s', $rateType),
                    'POSTNL-0036'
                );
        }

        Mage::register('current_postnl_shipping_method', $shippingMethod);
        return $shippingMethod;
    }

    /**
     * Get a shipping rate for a parcel only.
     *
     * @param Mage_Sales_Model_Quote $quote
     *
     * @return Mage_Shipping_Model_Rate_Result_Method|false
     */
    public function getParcelShippingRate(Mage_Sales_Model_Quote $quote)
    {
        $registryKey = 'postnl_parcel_shipping_rate_quote_id_' . $quote->getId();
        if (Mage::registry($registryKey) !== null) {
            return Mage::registry($registryKey);
        }

        $shippingAddress = $quote->getShippingAddress();
        if (!$shippingAddress) {
            Mage::register($registryKey, false);
            return false;
        }

        $store = $quote->getStore();

        /** @var $request Mage_Shipping_Model_Rate_Request */
        $request = Mage::getModel('shipping/rate_request');
        $request->setAllItems($shippingAddress->getAllItems());
        $request->setDestCountryId($shippingAddress->getCountryId());
        $request->setDestRegionId($shippingAddress->getRegionId());
        $request->setDestRegionCode($shippingAddress->getRegionCode());

        /**
         * need to call getStreet with -1
         * to get data in string instead of array
         */
        $request->setDestStreet($shippingAddress->getStreet($shippingAddress::DEFAULT_DEST_STREET));
        $request->setDestCity($shippingAddress->getCity());
        $request->setDestPostcode($shippingAddress->getPostcode());
        $request->setPackageValue($shippingAddress->getBaseSubtotal());
        $packageValueWithDiscount = $shippingAddress->getBaseSubtotalWithDiscount();
        $request->setPackageValueWithDiscount($packageValueWithDiscount);
        $request->setPackageWeight($shippingAddress->getWeight());
        $request->setPackageQty($shippingAddress->getItemQty());

        /**
         * Need for shipping methods that use insurance based on price of physical products
         */
        $packagePhysicalValue = $shippingAddress->getBaseVirtualAmount();
        $request->setPackagePhysicalValue($packagePhysicalValue);

        $request->setFreeMethodWeight($shippingAddress->getFreeMethodWeight());

        $request->setStoreId($store->getId());
        $request->setWebsiteId($store->getWebsiteId());
        $request->setFreeShipping($shippingAddress->getFreeShipping());
        /**
         * Currencies need to convert in free shipping
         */
        $request->setBaseCurrency($store->getBaseCurrency());
        $request->setPackageCurrency($store->getCurrentCurrency());
        $request->setLimitCarrier($shippingAddress->getLimitCarrier());

        $request->setBaseSubtotalInclTax(
            $shippingAddress->getBaseSubtotalInclTax() + $shippingAddress->getBaseExtraTaxAmount()
        );
        $request->setParcelType('regular');

        $result = Mage::getResourceModel('postnl_carrier/matrixrate')->getRate($request);
        if (!$result) {
            Mage::register($registryKey, false);
            return false;
        }

        $result = Mage::getModel('shipping/shipping')
                     ->collectCarrierRates('postnl', $request)
                     ->getResult();

        $rates = $result->getAllRates();
        if (empty($rates)) {
            Mage::register($registryKey, false);
            return false;
        }

        /**
         * Return the first rate found (there should only be 1).
         */
        $rate = $rates[0];

        Mage::register($registryKey, $rate);
        return $rate;
    }

    /**
     * Checks if a specified shipping method is a PostNL shipping method.
     *
     * @param $shippingMethod
     *
     * @return bool
     */
    public function isPostnlShippingMethod($shippingMethod)
    {
        /**
         * Check if we've matched this shipping method before.
         */
        $matchedMethods = $this->getMatchedMethods();
        if (isset($matchedMethods[$shippingMethod])) {
            return $matchedMethods[$shippingMethod];
        }

        /**
         * Check if the shipping method exists in the configured array of supported methods.
         */
        $postnlShippingMethods = $this->getPostnlShippingMethods();
        if (in_array($shippingMethod, $postnlShippingMethods)) {
            $this->addMatchedMethod($shippingMethod, true);
            return true;
        }

        /**
         * Some shipping methods add suffixes to the method code.
         */
        foreach ($postnlShippingMethods as $postnlShippingMethod) {
            $regex = "/^({$postnlShippingMethod})(_?\d*)$/";

            if (preg_match($regex, $shippingMethod) === 1) {
                $this->addMatchedMethod($shippingMethod, true);
                return true;
            }
        }

        $this->addMatchedMethod($shippingMethod, false);
        return false;
    }

    /**
     * Constructs a PostNL track & trace url based on a barcode and the destination of the package (country and
     * zipcode).
     *
     * @param string              $barcode
     * @param array|Varien_Object $destination An array or object containing the shipment's destination data.
     * @param boolean|string      $lang
     * @param boolean             $forceNl
     *
     * @return string
     */
    public function getBarcodeUrl($barcode, $destination, $lang = false, $forceNl = false)
    {
        $countryCode = null;
        $postcode    = null;
        if (is_array($destination)) {
            if (!isset($destination['countryCode']) || !isset($destination['postcode'])) {
                throw new InvalidArgumentException("Destination must contain the 'countryCode' and 'postcode' keys.");
            }

            $countryCode = $destination['countryCode'];
            $postcode    = $destination['postcode'];
        } elseif (is_object($destination) && $destination instanceof Varien_Object) {
            if (!$destination->getCountry() || !$destination->getPostcode()) {
                throw new InvalidArgumentException('Destination must have a country and a postcode.');
            }

            $countryCode = $destination->getCountry();
            $postcode    = str_replace(' ', '', $destination->getPostcode());
        } else {
            throw new InvalidArgumentException('Destination must be an array or an instance of Varien_Object.');
        }

        /**
         * Get the dutch track & trace URL for dutch shipments or for the admin
         */
        if ($forceNl
            || (!empty($countryCode)
                && $countryCode == 'NL'
            )
        ) {
            $barcodeUrl = Mage::getStoreConfig(self::POSTNL_TRACK_AND_TRACE_NL_BASE_URL_XPATH)
                        . '&b=' . $barcode;
            /**
             * For dutch shipments add the postcode. For international shipments add an 'international' flag
             */
            if (!empty($postcode)
                && !empty($countryCode)
                && $countryCode == 'NL'
            ) {
                $barcodeUrl .= '&p=' . $postcode;
            } else {
                $barcodeUrl .= '&i=true';
            }

            return $barcodeUrl;
        }

        /**
         * Get localized track & trace URLs for UK, DE and FR shipments
         */
        if (isset($countryCode)
            && ($countryCode == 'UK'
                || $countryCode == 'GB'
            )
        ) {
            $barcodeUrl = Mage::getStoreConfig(self::POSTNL_TRACK_AND_TRACE_GB_BASE_URL_XPATH)
                        . '&B=' . $barcode
                        . '&D=GB'
                        . '&lang=en';

            return $barcodeUrl;
        }

        if (isset($countryCode) && $countryCode == 'DE') {
            $barcodeUrl = Mage::getStoreConfig(self::POSTNL_TRACK_AND_TRACE_DE_BASE_URL_XPATH)
                        . '&B=' . $barcode
                        . '&D=DE'
                        . '&lang=de';

            return $barcodeUrl;
        }

        if (isset($countryCode) && $countryCode == 'FR') {
            $barcodeUrl = Mage::getStoreConfig(self::POSTNL_TRACK_AND_TRACE_FR_BASE_URL_XPATH)
                        . '&B=' . $barcode
                        . '&D=FR'
                        . '&lang=fr';

            return $barcodeUrl;
        }

        /**
         * Get a general track & trace URL for all other destinations
         */
        $barcodeUrl = Mage::getStoreConfig(self::POSTNL_TRACK_AND_TRACE_INT_BASE_URL_XPATH)
                    . '&B=' . $barcode
                    . '&I=true';

        if ($lang) {
            $barcodeUrl .= '&lang=' . strtolower($lang);
        }

        if ($countryCode) {
            $barcodeUrl .= '&D=' . $countryCode;
        }

        return $barcodeUrl;
    }
}
