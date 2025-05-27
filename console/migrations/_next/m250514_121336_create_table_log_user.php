<?php

namespace _next;

use yii\db\Migration;

class m250514_121336_create_table_log_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%log_user}}',
            [
                'id_log_user' => $this->primaryKey(),
                'email' => $this->string(),
                'date' => $this->date()->comment('
        '),
                'waktu' => $this->string(),
                'id_user' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%log_user}}');
    }
}
