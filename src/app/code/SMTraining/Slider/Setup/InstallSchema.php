<?php

namespace SMTraining\Slider\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        /**
         * create table sm_cms_slider
         */
        $tableName = $setup->getTable('sm_cms_slider');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Slider ID'
                )->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Name'
                )->addColumn(
                    'location',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => true],
                    'Location'
                )->addColumn(
                    'enable',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => true],
                    'Enable'
                )->addIndex(
                    $setup->getIdxName(
                        $setup->getTable('sm_cms_slider'),
                        'name',
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ), 'name',
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]);

            $setup->getConnection()->createTable($table);
        }

        /**
         * create table sm_cms_slider_slide
         */
        $tableName = $setup->getTable('sm_cms_slider_slide');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'slide_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Slide ID'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    [
                        'nullable' => true
                    ],
                    'Name'
                )->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => true
                    ],
                    'Image'
                )->addColumn(
                    'enable',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'nullable' => true
                    ],
                    'Enable'
                )->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true
                    ],
                    'Slider Dd'
                )->addIndex(
                    $setup->getIdxName($setup->getTable('sm_cms_slider_slide'), ['slider_id']),
                    ['slider_id']
                )->addIndex(
                    $setup->getIdxName(
                        $setup->getTable('sm_cms_slider_slide'),
                        ['name'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['name'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
                )->addForeignKey(
                    $setup->getFkName('sm_cms_slider_slide', 'slider_id', 'sm_cms_slider', 'slider_id'),
                    'slider_id',
                    $setup->getTable('sm_cms_slider'),
                    'slider_id',
                    Table::ACTION_NO_ACTION
                );

            $setup->getConnection()->createTable($table);
        }

        /**
         * create sm_cms_slider_store table
         */
        $tableName = $setup->getTable('sm_cms_slider_store');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'slider_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true,
                    ],
                    'Slide ID'
                )->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true,
                    ],
                    'Store ID'
                )->addIndex(
                    $setup->getIdxName('sm_cms_slider_store', ['slider_id']),
                    ['slider_id']
                )->addIndex(
                    $setup->getIdxName('sm_cms_slider_store', ['store_id']),
                    ['store_id']
                )->addForeignKey(
                    $setup->getFkName('sm_cms_slider_store', 'slider_id', 'sm_cms_slider', 'slider_id'),
                    'slider_id',
                    $setup->getTable('sm_cms_slider'),
                    'slider_id',
                    Table::ACTION_CASCADE
                )->addForeignKey(
                    $setup->getFkName('sm_cms_slider_store', 'slider_id', 'store', 'store_id'),
                    'store_id',
                    $setup->getTable('store'),
                    'store_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Author To Store Link Table');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}