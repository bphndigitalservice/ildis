<?php

use yii\db\Migration;

class m250514_121415_create_table_urusan_pemerintahan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%urusan_pemerintahan}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(),
                'status' => $this->string(),
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
        $this->dropTable('{{%urusan_pemerintahan}}');
    }
}
