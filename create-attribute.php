<?php
ini_set('display_errors', 1);
use \Magento\Framework\App\Bootstrap;
include('app/bootstrap.php');
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$setup=$objectManager->get('\Magento\Framework\Setup\ModuleDataSetupInterface');
$eavSetupFactory = $objectManager->get('\Magento\Eav\Setup\EavSetupFactory');

		$setup->startSetup();

        /** @var EavSetup $eavSetup */
        $eavSetup = $eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Category::ENTITY,
            'page_password',
                      [
                        'type' => 'varchar',
                        'group' => 'General Information',
                        'label' => 'Page Password',
                        'input' => 'text',
                        'required' => false,
                        'sort_order' => 100,
                        'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                        'group' => 'General Information',
                        'is_used_in_grid' => true,
                        'is_visible_in_grid' => false,
                        'is_filterable_in_grid' => true,
                        'visible'           => true,
                        'visible_on_front'  => true,
            ]
        );

        $setup->endSetup();
    

       
?>
