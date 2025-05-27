<?php

namespace _next;

use yii\db\Migration;

class m250514_121354_create_table_putusan_terkait extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%putusan_terkait}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'putusan_terkait' => $this->string(),
                'kasasi_puter' => $this->string(),
                'banding_puter' => $this->string(),
                'pertama_puter' => $this->string(),
                'integrasi' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'urutan' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%putusan_terkait}}');
    }
}
