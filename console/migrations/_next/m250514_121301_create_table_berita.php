<?php

namespace _next;

use yii\db\Migration;

class m250514_121301_create_table_berita extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%berita}}',
            [
                'id' => $this->primaryKey(),
                'tanggal' => $this->date()->notNull(),
                'judul' => $this->string()->notNull(),
                'isi' => $this->text()->notNull(),
                'image' => $this->text(),
                'status' => $this->integer(),
                'created_at' => $this->dateTime(),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime(),
                'updated_by' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%berita}}');
    }
}
