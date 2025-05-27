<?php

namespace _next;

use yii\db\Migration;

class m250514_121417_create_table_user_member extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user_member}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue('10'),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
            ],
            $tableOptions
        );

        $this->createIndex('email', '{{%user_member}}', ['email'], true);
        $this->createIndex('password_reset_token', '{{%user_member}}', ['password_reset_token'], true);
        $this->createIndex('username', '{{%user_member}}', ['username'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_member}}');
    }
}
