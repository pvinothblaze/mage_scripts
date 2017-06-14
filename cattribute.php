<?php
ini_set('display_errors','1');
use \Magento\Framework\App\Bootstrap;
include('../app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$eavSetupFactory = $objectManager->get('\Magento\Eav\Setup\EavSetupFactory');
$eavSetup = $eavSetupFactory->create();
 /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'custom_attribute',
            [
                'group' => 'General Information',
                'type' => 'varchar',
                'label' => 'Custom Attribute',
                'input' => 'text',
                'required' => false,
                'sort_order' => 100,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'user_defined' => true,
                'is_used_in_grid' => true,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => true,
            ]
        );
?>
