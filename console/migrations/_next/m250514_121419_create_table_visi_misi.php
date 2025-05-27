<?php

namespace _next;

use yii\db\Migration;

class m250514_121419_create_table_visi_misi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%visi_misi}}',
            [
                'id' => $this->primaryKey(),
                'visi_misi' => $this->text(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%visi_misi}}');
    }
}
