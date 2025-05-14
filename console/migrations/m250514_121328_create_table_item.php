<?php

use yii\db\Migration;

class m250514_121328_create_table_item extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%item}}',
            [
                'id' => $this->primaryKey(),
                'id_dokumen' => $this->integer(),
                'title' => $this->string(),
                'call_number' => $this->string(50),
                'coll_type_id' => $this->integer(),
                'item_code' => $this->string(20),
                'inventory_code' => $this->string(200),
                'received_date' => $this->date(),
                'supplier_id' => $this->string(6),
                'order_no' => $this->string(20),
                'location_id' => $this->string(3),
                'order_date' => $this->date(),
                'item_status_id' => $this->char(3),
                'site' => $this->string(50),
                'source' => $this->integer()->defaultValue('0'),
                'invoice' => $this->string(20),
                'price' => $this->string(155),
                'price_currency' => $this->string(10),
                'invoice_date' => $this->date(),
                'uid' => $this->integer(),
                'status' => $this->integer(),
                '_created_by' => $this->integer(),
                '_updated_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'gmd' => $this->string(),
            ],
            $tableOptions
        );

        $this->createIndex('biblio_id_idx', '{{%item}}', ['id_dokumen']);
        $this->createIndex('item_code', '{{%item}}', ['item_code'], true);
        $this->createIndex('item_references_idx', '{{%item}}', ['coll_type_id', 'location_id', 'item_status_id']);
        $this->createIndex('uid', '{{%item}}', ['uid']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%item}}');
    }
}
