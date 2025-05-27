<?php

namespace _next;

use yii\db\Migration;

class m250514_121256_create_table_auth_assignment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%auth_assignment}}',
            [
                'item_name' => $this->string(64)->notNull(),
                'user_id' => $this->string(64)->notNull(),
                'created_at' => $this->integer(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%auth_assignment}}', ['item_name', 'user_id']);

        $this->createIndex('idx-auth_assignment-user_id', '{{%auth_assignment}}', ['user_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth_assignment}}');
    }
}
