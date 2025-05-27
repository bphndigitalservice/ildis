<?php

namespace _next;

use yii\db\Migration;

class m250514_121311_create_table_data_subyek extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%data_subyek}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'subyek' => $this->string()->notNull(),
                'tipe_subyek' => $this->string()->notNull(),
                'jenis_subyek' => $this->string()->notNull(),
                'status' => $this->string(),
                'integrasi' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $tableOptions
        );

        $this->createIndex('id_dokumen', '{{%data_subyek}}', ['id_dokumen']);
        $this->createIndex('subyek', '{{%data_subyek}}', ['subyek']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%data_subyek}}');
    }
}
