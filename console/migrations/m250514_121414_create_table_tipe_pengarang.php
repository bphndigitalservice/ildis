<?php

use yii\db\Migration;

class m250514_121414_create_table_tipe_pengarang extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%tipe_pengarang}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->string()->notNull(),
                'orders' => $this->string(11),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->date(),
                'updated_at' => $this->date(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%tipe_pengarang}}');
    }
}
