<?php

use yii\db\Migration;

class m250514_121327_create_table_integrasi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%integrasi}}',
            [
                'id' => $this->primaryKey(),
                'domain' => $this->string(),
                'username' => $this->string(),
                'password' => $this->string(),
                'keterangan' => $this->text(),
                '_created_by' => $this->integer(),
                '_updated_by' => $this->integer(),
                '_created_time' => $this->dateTime()->notNull(),
                '_updated_time' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%integrasi}}');
    }
}
