<?php

namespace _next;

use yii\db\Migration;

class m250514_121411_create_table_tempat_penetapan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tempat_penetapan}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->string(),
                'integrasi' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('name', '{{%tempat_penetapan}}', ['name']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%tempat_penetapan}}');
    }
}
