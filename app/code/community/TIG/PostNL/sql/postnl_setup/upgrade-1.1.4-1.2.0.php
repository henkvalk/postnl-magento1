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

/**
 * @var TIG_PostNL_Model_Resource_Setup $installer
 */
$installer = $this;

$installer->startSetup();

$conn = $installer->getConnection();

/***********************************************************************************************************************
 * POSTNL SHIPMENT
 **********************************************************************************************************************/

$conn->addColumn($installer->getTable('postnl_core/shipment'),
    'is_parcelware_exported',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable' => false,
        'default'  => 0,
        'comment'  => 'Is Parcelware Exported',
        'after'    => 'labels_printed',
    )
);

$conn->addColumn($installer->getTable('postnl_core/shipment'),
    'delivery_date',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        'nullable' => true,
        'comment'  => 'Delivery Date',
        'after'    => 'confirm_date',
    )
);

$conn->addColumn($installer->getTable('postnl_core/shipment'),
    'is_pakketautomaat',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable' => false,
        'default'  => 0,
        'comment'  => 'Is Pakketautomaat',
        'after'    => 'is_pakje_gemak',
    )
);

/**
 * Modify the shipment_type column to avoid confusion with the PostNL order's 'type' column.
 */
$conn->changeColumn($installer->getTable('postnl_core/shipment'),
    'shipment_type',
    'globalpack_shipment_type',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'length'   => 32,
        'nullable' => true,
        'comment'  => 'GlobalPack Shipment Type',
    )
);

/***********************************************************************************************************************
 * POSTNL ORDER
 **********************************************************************************************************************/

$conn->addColumn($installer->getTable('postnl_checkout/order'),
    'type',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'default'  => null,
        'comment'  => 'Type',
        'after'    => 'quote_id',
    )
);

$conn->addColumn($installer->getTable('postnl_checkout/order'),
    'shipment_costs',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_FLOAT,
        'nullable' => true,
        'default'  => 0,
        'comment'  => 'Shipment Costs',
        'after'    => 'product_code',
    )
);

$conn->addColumn($installer->getTable('postnl_checkout/order'),
    'mobile_phone_number',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'default'  => null,
        'comment'  => 'Mobile Phone Number',
        'after'    => 'is_pakje_gemak',
    )
);

$conn->addColumn($installer->getTable('postnl_checkout/order'),
    'is_pakketautomaat',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable' => true,
        'default'  => 0,
        'comment'  => 'Is Pakketautomaat',
        'after'    => 'is_pakje_gemak',
    )
);

/***********************************************************************************************************************
 * POSTNL TABLERATE
 **********************************************************************************************************************/

$table = $installer->getConnection()
                   ->newTable($installer->getTable('postnl_carrier/tablerate'));

$table->addColumn('pk', Varien_Db_Ddl_Table::TYPE_INTEGER, null,
    array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Primary key')
    ->addColumn('website_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Website Id')
    ->addColumn('dest_country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 4, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Destination country ISO/2 or ISO/3 code')
    ->addColumn('dest_region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Destination Region Id')
    ->addColumn('dest_zip', Varien_Db_Ddl_Table::TYPE_TEXT, 10, array(
        'nullable'  => false,
        'default'   => '*',
    ), 'Destination Post Code (Zip)')
    ->addColumn('condition_name', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
        'nullable'  => false,
    ), 'Rate Condition name')
    ->addColumn('condition_value', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
    ), 'Rate condition value')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
    ), 'Price')
    ->addColumn('cost', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
    ), 'Cost')
    ->addIndex(
        $installer->getIdxName(
            'postnl_carrier/tablerate',
            array(
                'website_id',
                'dest_country_id',
                'dest_region_id',
                'dest_zip',
                'condition_name',
                'condition_value'
            ),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array(
            'website_id',
            'dest_country_id',
            'dest_region_id',
            'dest_zip',
            'condition_name',
            'condition_value'
        ),
        array(
            'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        )
    )
    ->setComment('PostNL Tablerate');

$installer->getConnection()->createTable($table);

$installer->endSetup();