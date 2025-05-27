<?php

namespace _next;

use yii\db\Migration;

class m250514_121355_create_table_rekaman_aktif extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%rekaman_aktif}}',
            [
                'id' => $this->primaryKey(),
                'id_inisialisasi' => $this->integer(),
                'id_eksemplar' => $this->string(),
                'status' => $this->integer(),
                'aktif' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%rekaman_aktif}}');
    }
}
