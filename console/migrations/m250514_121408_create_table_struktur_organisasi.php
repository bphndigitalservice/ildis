<?php

use yii\db\Migration;

class m250514_121408_create_table_struktur_organisasi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%struktur_organisasi}}',
            [
                'id' => $this->primaryKey(),
                'image' => $this->text(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%struktur_organisasi}}');
    }
}
