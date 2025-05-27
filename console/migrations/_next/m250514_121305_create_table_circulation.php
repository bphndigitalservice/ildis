<?php

namespace _next;

use yii\db\Migration;

class m250514_121305_create_table_circulation extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%circulation}}',
            [
                'id' => $this->primaryKey(),
                'member_id' => $this->integer(),
                'member' => $this->string(),
                'document_id' => $this->integer(),
                'title' => $this->string(),
                'item_id' => $this->integer(),
                'item_code' => $this->string(),
                'tanggal_pinjam' => $this->date(),
                'tanggal_kembali' => $this->date(),
                'status' => $this->integer(),
                'denda' => $this->integer(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'status_peminjaman' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%circulation}}');
    }
}
