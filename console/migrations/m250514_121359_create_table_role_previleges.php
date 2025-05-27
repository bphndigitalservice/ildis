<?php

use yii\db\Migration;

class m250514_121359_create_table_role_previleges extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%role_previleges}}',
            [
                'id' => $this->primaryKey(),
                'id_role' => $this->string()->notNull(),
                'id_previleges' => $this->string()->notNull(),
                'created_at' => $this->date(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%role_previleges}}');
    }
}
