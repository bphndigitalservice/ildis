<?php

use yii\db\Migration;

class m250514_121347_create_table_peminjaman extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%peminjaman}}',
            [
                'id' => $this->primaryKey(),
                'member_id' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%peminjaman}}');
    }
}
