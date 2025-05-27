<?php

use yii\db\Migration;

class m250514_121320_create_table_eksemplar extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%eksemplar}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer(),
                'kode_eksemplar' => $this->string(),
                'no_panggil' => $this->string(),
                'kode_inventaris' => $this->string(),
                'id_lokasi' => $this->string(),
                'lokasi_rak' => $this->string(),
                'tipe_lokasi' => $this->string(),
                'status_eksemplar' => $this->string(),
                'nomor_pemesanan' => $this->string(),
                'tgl_pemesanan' => $this->date(),
                'tgl_penerimaan' => $this->date(),
                'agen' => $this->string(),
                'sumber_perolehan' => $this->string(),
                'faktur' => $this->string(),
                'tgl_faktur' => $this->date(),
                'harga' => $this->integer(),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
                'barcode_image' => $this->string(),
            ],
            $tableOptions
        );

        $this->createIndex('id_dokumen', '{{%eksemplar}}', ['id_dokumen']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%eksemplar}}');
    }
}
