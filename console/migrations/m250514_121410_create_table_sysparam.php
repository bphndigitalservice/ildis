<?php

use yii\db\Migration;

class m250514_121410_create_table_sysparam extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%sysparam}}',
            [
                'id' => $this->primaryKey(),
                'groups' => $this->string(),
                'key' => $this->string(),
                'value' => $this->string(),
                'long_value' => $this->text(),
                'order_param' => $this->integer(),
                'status' => $this->string(),
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
        $this->dropTable('{{%sysparam}}');
    }
}
