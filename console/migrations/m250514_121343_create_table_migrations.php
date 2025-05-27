<?php

use yii\db\Migration;

class m250514_121343_create_table_migrations extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%migrations}}',
            [
                'id' => $this->primaryKey()->unsigned(),
                'migration' => $this->string()->notNull(),
                'batch' => $this->integer()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%migrations}}');
    }
}
