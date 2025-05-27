<?php

namespace _next;

use yii\db\Migration;

class m250514_121338_create_table_makna_logo extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%makna_logo}}',
            [
                'id' => $this->primaryKey(),
                'image' => $this->text(),
                'isi' => $this->text(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%makna_logo}}');
    }
}
