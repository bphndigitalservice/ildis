<?php

namespace _next;

use yii\db\Migration;

class m250514_121418_create_table_user_member_profile extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user_member_profile}}',
            [
                'id' => $this->primaryKey(),
                'member_name' => $this->string(100),
                'gender' => $this->string(100),
                'birth_date' => $this->date(),
                'member_type_id' => $this->string(100),
                'member_address' => $this->text(),
                'member_email' => $this->string(),
                'postal_code' => $this->string(100),
                'personal_id_number' => $this->string(100),
                'inst_name' => $this->string(100),
                'member_image' => $this->text(),
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
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_member_profile}}');
    }
}
