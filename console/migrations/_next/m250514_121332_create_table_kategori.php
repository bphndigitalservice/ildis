<?php

namespace _next;

use yii\db\Migration;

class m250514_121332_create_table_kategori extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%kategori}}',
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->string()->notNull(),
                'nama_kategori' => $this->string()->notNull(),
                'status' => $this->string(),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%kategori}}');
    }
}
