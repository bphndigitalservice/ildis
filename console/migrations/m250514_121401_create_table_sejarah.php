<?php

use yii\db\Migration;

class m250514_121401_create_table_sejarah extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%sejarah}}',
            [
                'id' => $this->primaryKey(),
                'isi' => $this->text(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%sejarah}}');
    }
}
