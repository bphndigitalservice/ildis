<?php

use yii\db\Migration;

class m250514_121312_create_table_denda extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%denda}}',
            [
                'id' => $this->primaryKey(),
                'id_detail_peminjaman' => $this->string(),
                'tunai' => $this->string(),
                'status' => $this->string(),
                'deskripsi_denda' => $this->string(),
                'tanggal_denda' => $this->date(),
                'member_id' => $this->string(11),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%denda}}');
    }
}
