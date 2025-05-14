<?php

use yii\db\Migration;

class m250514_121324_create_table_hasil_uji_materi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%hasil_uji_materi}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'hasil_uji_materi' => $this->string(),
                'status_hasum' => $this->string(),
                'catatan_hasum' => $this->string(),
                'integrasi' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'urutan' => $this->integer(),
                '_updated_by' => $this->string(),
                '_created_by' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%hasil_uji_materi}}');
    }
}
