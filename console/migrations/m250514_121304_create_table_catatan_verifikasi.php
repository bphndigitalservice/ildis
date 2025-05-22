<?php

use yii\db\Migration;

class m250514_121304_create_table_catatan_verifikasi extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%catatan_verifikasi}}',
            [
                'id' => $this->primaryKey(),
                'dokumen_id' => $this->integer()->notNull(),
                'catatan' => $this->text()->notNull(),
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
        $this->dropTable('{{%catatan_verifikasi}}');
    }
}
