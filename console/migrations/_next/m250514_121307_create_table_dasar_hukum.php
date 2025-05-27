<?php

namespace _next;

use yii\db\Migration;

class m250514_121307_create_table_dasar_hukum extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%dasar_hukum}}',
            [
                'id' => $this->primaryKey(),
                'isi' => $this->text(),
                'dokumen' => $this->text(),
                'status' => $this->integer(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%dasar_hukum}}');
    }
}
