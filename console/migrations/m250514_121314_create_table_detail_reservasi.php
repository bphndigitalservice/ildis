<?php

use yii\db\Migration;

class m250514_121314_create_table_detail_reservasi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%detail_reservasi}}',
            [
                'id' => $this->primaryKey(),
                'id_reservasi' => $this->string(),
                'kode_buku' => $this->string(),
                'tgl_reservasi' => $this->string(),
                'status' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%detail_reservasi}}');
    }
}
