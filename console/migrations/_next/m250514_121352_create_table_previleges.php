<?php

namespace _next;

use yii\db\Migration;

class m250514_121352_create_table_previleges extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%previleges}}',
            [
                'id' => $this->primaryKey(),
                'nama_previleges' => $this->string(),
                'icon' => $this->string(),
                'ordering' => $this->string(),
                'parent' => $this->string(),
                'uri' => $this->string(),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
                'ket' => $this->string(),
                'flagsub' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%previleges}}');
    }
}
