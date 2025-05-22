<?php

use yii\db\Migration;

class m250514_121348_create_table_penerbit extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%penerbit}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->string()->notNull(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('name', '{{%penerbit}}', ['name']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%penerbit}}');
    }
}
