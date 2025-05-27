<?php

namespace _next;

use yii\db\Migration;

class m250514_121318_create_table_dokumen_data_subyek extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%dokumen_data_subyek}}',
            [
                'id' => $this->integer()->notNull()->defaultValue('0'),
                'tipe_dokumen' => $this->integer(),
                'judul' => $this->text()->notNull(),
                'teu' => $this->text(),
                'nomor_peraturan' => $this->string(),
                'nomor_panggil' => $this->string(),
                'dokumen_type_id' => $this->integer()->comment('penambahan baru'),
                'bentuk_peraturan' => $this->text(),
                'jenis_peraturan' => $this->string()->defaultValue('-'),
                'singkatan_jenis' => $this->string()->defaultValue('-'),
                'cetakan' => $this->string(),
                'tempat_terbit' => $this->text(),
                'penerbit' => $this->text(),
                'tanggal_penetapan' => $this->date(),
                'deskripsi_fisik' => $this->string(),
                'sumber' => $this->text(),
                'isbn' => $this->string(),
                'bahasa' => $this->text(),
                'bidang_hukum' => $this->text(),
                'nomor_induk_buku' => $this->string(),
                'singkatan_bentuk' => $this->string(),
                'tipe_koleksi_nomor_eksemplar' => $this->string(),
                'pola_nomor_eksemplar' => $this->string(),
                'jumlah_eksemplar' => $this->string(),
                'kala_terbit' => $this->string(),
                'tahun_terbit' => $this->string(),
                'tanggal_dibacakan' => $this->date(),
                'pernyataan_tanggung_jawab' => $this->text(),
                'edisi' => $this->string(),
                'gmd' => $this->string(),
                'judul_seri' => $this->string(),
                'klasifikasi' => $this->string(),
                'info_detil_spesifik' => $this->string(),
                'abstrak' => $this->text(),
                'gambar_sampul' => $this->string(),
                'label' => $this->string(),
                'sembunyikan_di_opac' => $this->string(),
                'promosikan_ke_beranda' => $this->string()->defaultValue('Ya'),
                'status_terakhir' => $this->string(),
                'status' => $this->string()->defaultValue('Berlaku'),
                'integrasi' => $this->string(11)->defaultValue('1'),
                '_created_by' => $this->string(),
                '_updated_by' => $this->string(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
                'inisiatif' => $this->string(),
                'pemrakarsa' => $this->string(),
                'tanggal_pengundangan' => $this->date(),
                'daerah' => $this->integer(),
                'penandatanganan' => $this->string(),
                'lembaga_peradilan' => $this->string(),
                'pemohon' => $this->string(),
                'termohon' => $this->string(),
                'jenis_perkara' => $this->string(),
                'sub_klasifikasi' => $this->string(),
                'amar_status' => $this->string(),
                'berkekuatan_hukum_tetap' => $this->string(),
                'urusan_pemerintahan' => $this->string(),
                'catatan_status_peraturan' => $this->string(),
                'hit_see' => $this->integer(),
                'hit_download' => $this->integer(),
                'sumber_perolehan' => $this->string(),
                'is_publish' => $this->integer()->defaultValue('0'),
                'subjek_data' => $this->string(),
                'slug' => $this->string()->comment('slug'),
                'subyek' => $this->string(),
                'nama_pengarang' => $this->string(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%dokumen_data_subyek}}');
    }
}
