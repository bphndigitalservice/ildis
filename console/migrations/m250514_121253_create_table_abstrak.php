<?php

use yii\db\Migration;

class m250514_121253_create_table_abstrak extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%abstrak}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer(),
                'subjek' => $this->string()->comment('ambil dari database subjek'),
                'tahun' => $this->smallInteger(),
                'singkatan' => $this->string(),
                'judul' => $this->string(),
                'isi_abstrak_1' => $this->text(),
                'isi_abstrak_2' => $this->text(),
                'isi_abstrak_3' => $this->text(),
                'catatan_1' => $this->text(),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
                'catatan_2' => $this->text(),
                'catatan_3' => $this->text(),
                'catatan_4' => $this->text(),
                'catatan_5' => $this->text(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%abstrak}}');
    }
}
