<?php

namespace Dahlquist\Recipe\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    protected $resourceConnection;

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * {@inheritdoc}
     *
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), "1.0.1", "<")) {
            $connection = $this->resourceConnection->getConnection();
            $tableName = $this->resourceConnection->getTableName('email_template');
            
            if($tableName && !empty($tableName)){
                $sql = "UPDATE {$tableName} set is_legacy = 1 where orig_template_code = 'sales_email_order_template'";
            }

            $connection->query($sql);

            $setup->endSetup();
        }
    }
}