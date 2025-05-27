<?php

use yii\db\Migration;

class m250514_121405_create_table_stock_opname_monografi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%stock_opname_monografi}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer(),
                'tahun' => $this->smallInteger(),
                'jumlah_eksemplar' => $this->integer(),
                'jumlah_scan' => $this->integer(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%stock_opname_monografi}}');
    }
}
