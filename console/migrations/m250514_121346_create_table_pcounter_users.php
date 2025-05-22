<?php

use yii\db\Migration;

class m250514_121346_create_table_pcounter_users extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%pcounter_users}}',
            [
                'id' => $this->primaryKey(),
                'user_ip' => $this->string()->notNull(),
                'user_time' => $this->integer()->unsigned()->notNull(),
                'creation_date' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%pcounter_users}}');
    }
}
