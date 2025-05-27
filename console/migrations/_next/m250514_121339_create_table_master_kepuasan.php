<?php

namespace _next;

use yii\db\Migration;

class m250514_121339_create_table_master_kepuasan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%master_kepuasan}}',
            [
                'id' => $this->primaryKey(),
                'keterangan' => $this->string()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%master_kepuasan}}');
    }
}
