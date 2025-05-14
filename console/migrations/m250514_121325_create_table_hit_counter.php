<?php

use yii\db\Migration;

class m250514_121325_create_table_hit_counter extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%hit_counter}}',
            [
                'id' => $this->primaryKey(),
                'document_id' => $this->string(100),
                'identifier' => $this->string(100),
                'status' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(10),
                '_updated_by' => $this->string(10),
                '_created_time' => $this->dateTime(),
                '_updated_time' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%hit_counter}}');
    }
}
