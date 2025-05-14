<?php

use yii\db\Migration;

class m250514_121302_create_table_bidang_hukum extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%bidang_hukum}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull(),
                'status' => $this->string()->notNull(),
                'integrasi' => $this->integer()->defaultValue('1'),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%bidang_hukum}}');
    }
}
