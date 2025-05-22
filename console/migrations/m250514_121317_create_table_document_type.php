<?php

use yii\db\Migration;

class m250514_121317_create_table_document_type extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%document_type}}',
            [
                'id' => $this->primaryKey(),
                'second_id' => $this->string()->notNull(),
                'parent_id' => $this->integer()->notNull(),
                'name' => $this->string()->notNull(),
                'singkatan' => $this->string()->notNull(),
                'status' => $this->string(),
                'integrasi' => $this->integer()->defaultValue('1'),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('name', '{{%document_type}}', ['name']);
        $this->createIndex('parent_id', '{{%document_type}}', ['parent_id']);
        $this->createIndex('second_id', '{{%document_type}}', ['second_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%document_type}}');
    }
}
