<?php

use yii\db\Migration;

class m250514_121403_create_table_stock_opname extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%stock_opname}}',
            [
                'id' => $this->primaryKey(),
                'nama_inventarisasi' => $this->string()->notNull(),
                'gmd' => $this->string(50)->notNull(),
                'type_koleksi' => $this->string(50)->notNull(),
                'lokasi' => $this->string(50)->notNull(),
                'lokasi_rak' => $this->string(50)->notNull(),
                'klasifikasi' => $this->string()->notNull(),
                'stok_status' => $this->string(100),
                'tanggal_dibuat' => $this->dateTime()->notNull(),
                'tanggal_selesai' => $this->dateTime(),
                'created_by' => $this->integer()->notNull(),
                'updated_by' => $this->integer()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%stock_opname}}');
    }
}
