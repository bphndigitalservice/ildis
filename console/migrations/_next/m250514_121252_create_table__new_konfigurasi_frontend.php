<?php

namespace _next;

use yii\db\Migration;

class m250514_121252_create_table__new_konfigurasi_frontend extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%_new_konfigurasi_frontend}}',
            [
                'id' => $this->primaryKey(),
                'logo' => $this->string()->notNull(),
                'header1' => $this->string()->notNull(),
                'header2' => $this->string()->notNull(),
                'struktur_organisasi' => $this->string()->notNull(),
                'footer_bawah' => $this->string()->notNull(),
                'youtube' => $this->string(),
                'facebook' => $this->string(),
                'instagram' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%_new_konfigurasi_frontend}}');
    }
}
