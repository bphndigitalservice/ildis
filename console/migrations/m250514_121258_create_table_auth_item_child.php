<?php

use yii\db\Migration;

class m250514_121258_create_table_auth_item_child extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%auth_item_child}}',
            [
                'parent' => $this->string(64)->notNull(),
                'child' => $this->string(64)->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%auth_item_child}}', ['parent', 'child']);

        $this->createIndex('child', '{{%auth_item_child}}', ['child']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth_item_child}}');
    }
}
