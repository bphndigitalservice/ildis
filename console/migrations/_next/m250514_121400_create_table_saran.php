<?php

namespace _next;

use yii\db\Migration;

class m250514_121400_create_table_saran extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%saran}}',
            [
                'id' => $this->primaryKey(),
                'nama' => $this->string(),
                'email' => $this->string(),
                'saran' => $this->string(),
                'tanggal' => $this->date(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%saran}}');
    }
}
