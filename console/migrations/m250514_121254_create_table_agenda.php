<?php

use yii\db\Migration;

class m250514_121254_create_table_agenda extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%agenda}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(),
                'lokasi' => $this->string(),
                'date' => $this->date(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%agenda}}');
    }
}
