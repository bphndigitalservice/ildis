<?php

namespace _next;

use yii\db\Migration;

class m250514_121404_create_table_stock_opname_eksemplar extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%stock_opname_eksemplar}}',
            [
                'id' => $this->primaryKey(),
                'kode_eksemplar' => $this->string(100)->notNull(),
                'tanggal' => $this->date()->notNull(),
                'dokumen_id' => $this->integer()->notNull(),
                'tahun' => $this->smallInteger()->notNull(),
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
        $this->dropTable('{{%stock_opname_eksemplar}}');
    }
}
