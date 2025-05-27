<?php

namespace _next;

use yii\db\Migration;

class m250514_121358_create_table_role extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%role}}',
            [
                'id_role' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'created_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%role}}');
    }
}
