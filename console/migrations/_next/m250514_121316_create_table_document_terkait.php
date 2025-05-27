<?php

namespace _next;

use yii\db\Migration;

class m250514_121316_create_table_document_terkait extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%document_terkait}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer(),
                'id_dokumen_terkait' => $this->integer(),
                'document_terkait' => $this->string(),
                'status_docter' => $this->string(),
                'catatan_docter' => $this->string(),
                'integrasi' => $this->integer(),
                'urutan' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%document_terkait}}');
    }
}
