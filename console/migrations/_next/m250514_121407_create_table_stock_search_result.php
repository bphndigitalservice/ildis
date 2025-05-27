<?php

namespace _next;

use yii\db\Migration;

class m250514_121407_create_table_stock_search_result extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%stock_search_result}}',
            [
                'id' => $this->primaryKey(),
                'id_stock_opname' => $this->string(100)->notNull(),
                'nomor_panggil_doc' => $this->string()->notNull(),
                'name' => $this->string(100)->notNull(),
                'created_by' => $this->integer()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_by' => $this->integer()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%stock_search_result}}');
    }
}
