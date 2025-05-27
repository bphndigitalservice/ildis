<?php

namespace _next;

use yii\db\Migration;

class m250514_121313_create_table_detail_peminjaman extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%detail_peminjaman}}',
            [
                'id' => $this->primaryKey(),
                'id_peminjaman' => $this->string(),
                'kode_buku' => $this->string(),
                'tgl_pinjam' => $this->date(),
                'tgl_kembali' => $this->date(),
                'status' => $this->string(),
                'jumlah_perpanjangan' => $this->integer(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%detail_peminjaman}}');
    }
}
