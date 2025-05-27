<?php

namespace _next;

use yii\db\Migration;

class m250514_121330_create_table_kabupaten extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%kabupaten}}',
            [
                'id' => $this->char(4)->notNull()->append('PRIMARY KEY'),
                'province_id' => $this->char(2)->notNull(),
                'name' => $this->string()->notNull(),
                'created_at' => $this->date(),
                'updated_at' => $this->date(),
                'id_kab' => $this->char(2),
            ],
            $tableOptions
        );

        $this->createIndex('regencies_province_id_index', '{{%kabupaten}}', ['province_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%kabupaten}}');
    }
}
