<?php

namespace _next;

use yii\db\Migration;

class m250514_121306_create_table_daerah extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%daerah}}',
            [
                'id' => $this->primaryKey(),
                'nama' => $this->text()->notNull(),
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
        $this->dropTable('{{%daerah}}');
    }
}
