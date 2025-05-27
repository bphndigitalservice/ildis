<?php

use yii\db\Migration;

class m250514_121416_create_table_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string(),
                'email' => $this->string()->notNull(),
                'status' => $this->smallInteger()->notNull()->defaultValue('10'),
                'suspended_until' => $this->dateTime(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
                'picture' => $this->string()->defaultValue('avatar.png'),
                'updated_by' => $this->integer(),
            ],
            $tableOptions
        );

        $this->createIndex('email', '{{%user}}', ['email'], true);
        $this->createIndex('password_reset_token', '{{%user}}', ['password_reset_token'], true);
        $this->createIndex('username', '{{%user}}', ['username'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
