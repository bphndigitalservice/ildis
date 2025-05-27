<?php

use yii\db\Migration;

class m250514_121335_create_table_log_pustakawan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%log_pustakawan}}',
            [
                'id' => $this->primaryKey(),
                'controller' => $this->string(),
                'dokumen_id' => $this->integer(),
                'keterangan' => $this->text(),
                'aksi' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'created_by' => $this->string(),
                'updated_by' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%log_pustakawan}}');
    }
}
