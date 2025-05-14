<?php

use yii\db\Migration;

class m250514_121310_create_table_data_status extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%data_status}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'status_peraturan' => $this->string(),
                'id_dokumen_target' => $this->string(),
                'catatan_status_peraturan' => $this->text(),
                'tanggal_perubahan' => $this->dateTime(),
                'status' => $this->string(),
                'integrasi' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'urutan' => $this->integer(),
            ],
            $tableOptions
        );

        $this->createIndex('id_dokumen', '{{%data_status}}', ['id_dokumen']);
        $this->createIndex('id_dokumen_target', '{{%data_status}}', ['id_dokumen_target']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%data_status}}');
    }
}
