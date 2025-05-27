<?php

use yii\db\Migration;

class m250514_121409_create_table_survey_kepuasan extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%survey_kepuasan}}',
            [
                'id' => $this->primaryKey(),
                'tingkat_kepuasan' => $this->integer(),
                'ip_address' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'isi' => $this->string(1000),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%survey_kepuasan}}');
    }
}
