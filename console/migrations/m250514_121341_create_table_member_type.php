<?php

use yii\db\Migration;

class m250514_121341_create_table_member_type extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%member_type}}',
            [
                'id' => $this->primaryKey(),
                'member_type_name' => $this->string(50)->notNull()->comment('Nama tipe Member'),
                'loan_limit' => $this->integer()->notNull()->comment('jumlah_maksimal_peminjaman'),
                'loan_periode' => $this->integer()->notNull()->comment('lama_maksimal_peminjaman'),
                'enable_reserve' => $this->integer()->notNull()->defaultValue('0')->comment('status aktif/tidak'),
                'reserve_limit' => $this->integer()->notNull()->defaultValue('0')->comment('jumlah_maksimal_reservasi'),
                'member_periode' => $this->integer()->notNull()->comment('masa_berlaku_member'),
                'reborrow_limit' => $this->integer()->notNull()->comment('maksimal perpanjangan'),
                'fine_each_day' => $this->integer()->notNull()->comment('denda_perhari'),
                'grace_periode' => $this->integer()->defaultValue('0')->comment('toleransi_keterlambatan'),
                'input_date' => $this->date()->notNull(),
                'last_update' => $this->date(),
                'id_tipe_koleksi' => $this->string(),
                'id_tipe_gmd' => $this->string(),
                'created_by' => $this->integer(),
                'updated_by' => $this->integer(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('member_type_name', '{{%member_type}}', ['member_type_name'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%member_type}}');
    }
}
