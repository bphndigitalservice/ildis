<?php

namespace _next;

use yii\db\Migration;

class m250514_121255_create_table_audit_trail extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%audit_trail}}',
            [
                'id' => $this->primaryKey(),
                'user' => $this->string(50),
                'uri' => $this->string(),
                'method' => $this->string(50),
                'data' => $this->text(),
                'ip_address' => $this->string(),
                'user_agent' => $this->string(),
                'response' => $this->string(),
                'activity' => $this->string(),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%audit_trail}}');
    }
}
