<?php

use yii\db\Migration;

class m250514_121340_create_table_member extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%member}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string(100)->notNull(),
                'password_hash' => $this->string(100),
                'status' => $this->integer(),
                'member_name' => $this->string(100)->notNull(),
                'gender' => $this->string(100)->notNull(),
                'birth_date' => $this->date(),
                'member_type_id' => $this->string(100)->notNull(),
                'member_address' => $this->text(),
                'member_email' => $this->string()->notNull(),
                'postal_code' => $this->string(100),
                'personal_id_number' => $this->string(100),
                'inst_name' => $this->string(100),
                'member_image' => $this->text(),
                'member_ktp' => $this->text(),
                'member_since_date' => $this->date(),
                'register_date' => $this->date(),
                'expire_date' => $this->date(),
                'phone_number' => $this->string(50),
                'fax_number' => $this->string(50),
                'member_notes' => $this->text(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'auth_key' => $this->string(32),
                'password_reset_token' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%member}}');
    }
}
