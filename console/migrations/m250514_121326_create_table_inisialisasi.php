<?php

use yii\db\Migration;

class m250514_121326_create_table_inisialisasi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%inisialisasi}}',
            [
                'id' => $this->primaryKey(),
                'nama_inisialisasi' => $this->string(),
                'gmd' => $this->string(11),
                'tipe_koleksi' => $this->string(11),
                'lokasi' => $this->integer(),
                'lokasi_rak' => $this->string(),
                'klasifikasi' => $this->string(),
                'tanggal_dimulai' => $this->date(),
                'tanggal_berakhir' => $this->date(),
                'status' => $this->integer(),
                'pelaksana' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%inisialisasi}}');
    }
}
