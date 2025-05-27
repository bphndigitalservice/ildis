<?php

namespace _next;

use yii\db\Migration;

class m250514_121353_create_table_provinsi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%provinsi}}',
            [
                'id' => $this->char(2)->notNull()->append('PRIMARY KEY'),
                'name' => $this->string()->notNull(),
                'created_at' => $this->date(),
                'updated_at' => $this->date(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%provinsi}}');
    }
}
