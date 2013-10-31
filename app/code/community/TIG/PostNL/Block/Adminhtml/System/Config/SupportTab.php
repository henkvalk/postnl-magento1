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
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@totalinternetgroup.nl for more information.
 *
 * @copyright   Copyright (c) 2013 Total Internet Group B.V. (http://www.totalinternetgroup.nl)
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
class TIG_PostNL_Block_Adminhtml_System_Config_SupportTab
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    /**
     * Css file loaded for PostNL's system > config section
     */
    const SYSTEM_CONFIG_EDIT_CSS_FILE = 'css/TIG/PostNL/system_config_edit_postnl.css';
    
    /**
     * Template file used
     * 
     * @var string
     */
    protected $_template = 'TIG/PostNL/system/config/support_tab.phtml';
    
    /**
     * Variables used in template.
     * 
     * @var string
     * 
     * @todo change these to proper PostNL variables
     */
    public $buckarooSupport      = '<a href="mailto:support@buckaroo.nl">Buckaroo support</a>';
    public $anchorClose          = '</a>';
    public $totalEmail           = '<a href="mailto:info@totalinternetgroup.nl">';
    public $buckarooUrl          = '<a href="http://www.buckaroo.nl">Buckaroo</a>';
    
    /**
     * Add a new css file to the head. We couldn't do this from layout.xml, because it would have loaded 
     * for all System > Config pages, rather than just PostNL's section.
     * 
     * @return Mage_Adminhtml_Block_Abstract::_prepareLayout()
     * 
     * @see Mage_Adminhtml_Block_Abstract::_prepareLayout()
     */
    protected function _prepareLayout()
    {
        $this->getLayout()
             ->getBlock('head')
             ->addCss(self::SYSTEM_CONFIG_EDIT_CSS_FILE);
        
        return parent::_prepareLayout();
    }
    
    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }
}