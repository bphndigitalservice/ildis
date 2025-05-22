<?php

use yii\db\Migration;

class m250514_121344_create_table_notif extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%notif}}',
            [
                'id_notifikasi' => $this->primaryKey(),
                'judul' => $this->string(),
                'keterangan' => $this->string(),
                'waktu' => $this->time(),
                'id_terkait' => $this->string(),
                'url' => $this->string(),
                'status' => $this->integer()->defaultValue('0'),
                'notif_to' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%notif}}');
    }
}
