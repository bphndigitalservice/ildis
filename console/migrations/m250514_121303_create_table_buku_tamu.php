<?php

use yii\db\Migration;

class m250514_121303_create_table_buku_tamu extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%buku_tamu}}',
            [
                'id' => $this->primaryKey(),
                'nama_tamu' => $this->string()->notNull(),
                'institusi_tamu' => $this->string(),
                'tanggal_masuk' => $this->date(),
                'anggota' => $this->string(),
                'no_telp' => $this->string(100),
                'email' => $this->string(100),
                'keperluan' => $this->string(100),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%buku_tamu}}');
    }
}
