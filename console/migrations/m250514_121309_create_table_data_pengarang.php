<?php

use yii\db\Migration;

class m250514_121309_create_table_data_pengarang extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%data_pengarang}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer()->notNull(),
                'nama_pengarang' => $this->integer(),
                'tipe_pengarang' => $this->integer(),
                'jenis_pengarang' => $this->integer(),
                'status' => $this->string(),
                'integrasi' => $this->integer()->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                'created_at' => $this->date(),
                'updated_at' => $this->date(),
            ],
            $tableOptions
        );

        $this->createIndex('id_dokumen', '{{%data_pengarang}}', ['id_dokumen']);
        $this->createIndex('idjp_fk', '{{%data_pengarang}}', ['jenis_pengarang']);
        $this->createIndex('idtp_fk', '{{%data_pengarang}}', ['tipe_pengarang']);
        $this->createIndex('nama_pengarang', '{{%data_pengarang}}', ['nama_pengarang']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%data_pengarang}}');
    }
}
