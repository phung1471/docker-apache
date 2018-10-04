<?php

namespace SMTraining\Slider\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    )
    {
        if (version_compare($context->getVersion(), '2.0.3') < 0) {
            $installer = $setup;
            $installer->startSetup();

            $installer->getConnection()->addIndex(
                $installer->getTable('sm_cms_slider'),
                $setup->getIdxName(
                    $installer->getTable('sm_cms_slider'),
                    ['name'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                'name',   // filed or column name
                AdapterInterface::INDEX_TYPE_FULLTEXT //type of index
            );

            $installer->getConnection()->addIndex(
                $installer->getTable('sm_cms_slider_slide'),
                $setup->getIdxName(
                    $installer->getTable('sm_cms_slider_slide'),
                    ['name'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                'name',   // filed or column name
                AdapterInterface::INDEX_TYPE_FULLTEXT //type of index
            );

            $installer->endSetup();
        }
    }
}