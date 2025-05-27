<?php

namespace _next;

use yii\db\Migration;

class m250514_121406_create_table_stock_opname_tahun extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%stock_opname_tahun}}',
            [
                'id' => $this->primaryKey(),
                'tahun' => $this->smallInteger(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%stock_opname_tahun}}');
    }
}
