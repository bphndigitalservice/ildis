<?php

namespace _next;

use yii\db\Migration;

class m250514_121333_create_table_kecamatan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%kecamatan}}',
            [
                'id' => $this->char(7)->notNull()->append('PRIMARY KEY'),
                'regency_id' => $this->char(4)->notNull(),
                'name' => $this->string()->notNull(),
                'created_at' => $this->date(),
                'updated_at' => $this->date(),
                'id_kec' => $this->char(3),
            ],
            $tableOptions
        );

        $this->createIndex('districts_id_index', '{{%kecamatan}}', ['regency_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%kecamatan}}');
    }
}
