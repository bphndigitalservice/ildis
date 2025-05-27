<?php

namespace _next;

use yii\db\Migration;

class m250514_121308_create_table_data_lampiran extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%data_lampiran}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'judul_lampiran' => $this->string()->notNull(),
                'url_lampiran' => $this->string(),
                'deskripsi_lampiran' => $this->string(),
                'fulltext' => $this->string(),
                'akses_lampiran' => $this->string(),
                'dokumen_lampiran' => $this->string(),
                'pembatasan_lampiran' => $this->string(),
                'status' => $this->integer()->defaultValue('1'),
                'integrasi' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'urutan' => $this->integer(),
            ],
            $tableOptions
        );

        $this->createIndex('id_dokumen', '{{%data_lampiran}}', ['id_dokumen']);
        $this->createIndex('judul_lampiran', '{{%data_lampiran}}', ['judul_lampiran']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%data_lampiran}}');
    }
}
