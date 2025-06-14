<?php

namespace _next;

use yii\db\Migration;

class m250514_121356_create_table_report extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%report}}',
            [
                'id' => $this->primaryKey(),
                'jalan' => $this->string()->notNull(),
                'kota' => $this->string()->notNull(),
                'provinsi' => $this->string()->notNull(),
                'kode_pos' => $this->string()->notNull(),
                'telepon' => $this->string(100)->notNull(),
                'faksimili' => $this->string(100)->notNull(),
                'website_utama' => $this->string(100)->notNull(),
                'website_jdih' => $this->string(100)->notNull(),
                'email' => $this->string(100)->notNull(),
                'sop_pengolahan_jdih' => $this->text()->notNull(),
                'nama_biro' => $this->string(100)->notNull(),
                'nip_biro' => $this->string(100)->notNull(),
                'pangkat_biro' => $this->string(100)->notNull(),
                'klasifikasi_pendidikan_biro' => $this->string(100)->notNull(),
                'nama_sub' => $this->string(100)->notNull(),
                'nip_sub' => $this->string(100)->notNull(),
                'pangkat_sub' => $this->string(100)->notNull(),
                'klasifikasi_pendidikan_sub' => $this->string(100)->notNull(),
                'nama_jfu' => $this->string(100)->notNull(),
                'nip_jfu' => $this->string(100)->notNull(),
                'pangkat_jfu' => $this->string(100)->notNull(),
                'klasifikasi_pendidikan_jfu' => $this->string(100)->notNull(),
                'undang_undang' => $this->text()->notNull(),
                'peraturan_pemerintah_pengganti_uu' => $this->text()->notNull(),
                'peraturan_pemerintah' => $this->text()->notNull(),
                'peraturan_presiden' => $this->text()->notNull(),
                'peraturan_menteri' => $this->text()->notNull(),
                'peraturan_daerah_provinsi' => $this->text()->notNull(),
                'peraturan_daerah_kabupaten' => $this->text()->notNull(),
                'peraturan_daerah_kota' => $this->text()->notNull(),
                'peraturan_gubernur' => $this->text()->notNull(),
                'peraturan_bupati' => $this->text()->notNull(),
                'peraturan_walikota' => $this->text()->notNull(),
                'buku_hukum' => $this->string()->notNull(),
                'jurnal_hukum' => $this->string()->notNull(),
                'hasil_penelitian_hukum' => $this->string()->notNull(),
                'hasil_pengkajian_hukum' => $this->string()->notNull(),
                'naskah_akademis' => $this->string()->notNull(),
                'rancangan_peraturan_daerah' => $this->string()->notNull(),
                'keputusan_pengadilan' => $this->string()->notNull(),
                'yurispundensi' => $this->string()->notNull(),
                'artikel_hukum' => $this->string()->notNull(),
                'kliping_koran_berita_hukum' => $this->string()->notNull(),
                'lain_lain' => $this->string()->notNull(),
                'sudah_pedoman' => $this->string(100)->notNull(),
                'sudah_pedoman_jumlah' => $this->string(100)->notNull(),
                'sudah_iventarisasi' => $this->string(100)->notNull(),
                'sudah_iventarisasi_jumlah' => $this->string(100)->notNull(),
                'sudah_katalogisasi' => $this->string(100)->notNull(),
                'sudah_katalogisasi_jumlah' => $this->string(100)->notNull(),
                'sudah_abstrak' => $this->string(100)->notNull(),
                'sudah_abstrak_jumlah' => $this->string(100)->notNull(),
                'sudah_indeks' => $this->string(100)->notNull(),
                'sudah_indeks_jumlah' => $this->string(100)->notNull(),
                'sudah_majalah' => $this->string(100)->notNull(),
                'sudah_majalah_jumlah' => $this->string(100)->notNull(),
                'sudah_katalogisasi_hukum' => $this->string(100)->notNull(),
                'sudah_katalogisasi_jumlah_hukum' => $this->string(100)->notNull(),
                'ruang_kerja' => $this->string(100)->notNull(),
                'ruang_koleksi' => $this->string(100)->notNull(),
                'ruang_baca' => $this->string(100)->notNull(),
                'meja_baca' => $this->string(100)->notNull(),
                'kursi_baca' => $this->string(100)->notNull(),
                'komputer' => $this->string(100)->notNull(),
                'printer' => $this->string(100)->notNull(),
                'scanner' => $this->string(100)->notNull(),
                'koneksi_internet' => $this->string(100)->notNull(),
                'mesin_fotocopy' => $this->string(100)->notNull(),
                'writer' => $this->string(100)->notNull(),
                'telepon_sarana' => $this->string(100)->notNull(),
                'fak' => $this->string(100)->notNull(),
                'lain' => $this->string(100)->notNull(),
                'telah_otomasi' => $this->string(100)->notNull(),
                'memiliki_permasalahan' => $this->string(100)->notNull(),
                'memiliki_situs' => $this->string(100)->notNull(),
                'melakukan_pemutaakhiran' => $this->string(100)->notNull(),
                'web_dikelola' => $this->string(100)->notNull(),
                'jml_kegiatan_bimtek' => $this->string(100)->notNull(),
                'jml_peserta_bimtek' => $this->string(100)->notNull(),
                'ket_bimtek' => $this->string(100)->notNull(),
                'jml_kegiatan_sos' => $this->string(100)->notNull(),
                'jml_peserta_sos' => $this->string(100)->notNull(),
                'ket_sos' => $this->string(100)->notNull(),
                'jml_kegiatan_mon' => $this->string(100)->notNull(),
                'jml_peserta_mon' => $this->string(100)->notNull(),
                'ket_mon' => $this->string(100)->notNull(),
                'jml_kegiatan_koor' => $this->string(100)->notNull(),
                'jml_peserta_koor' => $this->string(100)->notNull(),
                'ket_koor' => $this->string(100)->notNull(),
                'jml_kegiatan_rakor' => $this->string(100)->notNull(),
                'jml_peserta_rakor' => $this->string(100)->notNull(),
                'ket_rakor' => $this->string(100)->notNull(),
                'jml_kegiatan_kons' => $this->string(100)->notNull(),
                'jml_peserta_kons' => $this->string(100)->notNull(),
                'ket_kons' => $this->string(100)->notNull(),
                'jml_kegiatan_mengikuti' => $this->string(100)->notNull(),
                'jml_peserta_mengikuti' => $this->string(100)->notNull(),
                'ket_mengikuti' => $this->string(100)->notNull(),
                'jml_kegiatan_kerja' => $this->string(100)->notNull(),
                'jml_peserta_kerja' => $this->string(100)->notNull(),
                'ket_kerja' => $this->string(100)->notNull(),
                'jml_kegiatan_kerjasama' => $this->string(100)->notNull(),
                'jml_peserta_kerjasama' => $this->string(100)->notNull(),
                'ket_kerjasama' => $this->string(100)->notNull(),
                'jml_kegiatan_mitra' => $this->string(100)->notNull(),
                'jml_peserta_mitra' => $this->string(100)->notNull(),
                'ket_mitra' => $this->string(100)->notNull(),
                'pengelola_jdih_prov' => $this->string()->notNull(),
                'website_jdih_prov' => $this->string()->notNull(),
                'status_integrasi_prov' => $this->string()->notNull(),
                'pengelola_jdih_kab' => $this->string()->notNull(),
                'website_jdih_kab' => $this->string()->notNull(),
                'status_integrasi_kab' => $this->string()->notNull(),
                'pengelola_jdih_kota' => $this->string()->notNull(),
                'website_jdih_kota' => $this->string()->notNull(),
                'status_integrasi_kota' => $this->string()->notNull(),
                'pengelola_jdih_dewprov' => $this->string()->notNull(),
                'website_jdih_dewprov' => $this->string()->notNull(),
                'status_integrasi_dewprov' => $this->string()->notNull(),
                'pengelola_jdih_dewpkab' => $this->string()->notNull(),
                'website_jdih_dewpkab' => $this->string()->notNull(),
                'status_integrasi_dewpkab' => $this->string()->notNull(),
                'pengelola_jdih_negri' => $this->string()->notNull(),
                'website_jdih_negri' => $this->string()->notNull(),
                'status_integrasi_negri' => $this->string()->notNull(),
                'pengelola_jdih_swasta' => $this->string()->notNull(),
                'website_jdih_swasta' => $this->string()->notNull(),
                'status_integrasi_swasta' => $this->string()->notNull(),
                'otomasi' => $this->string(100)->notNull(),
                'permasalahan' => $this->string(100)->notNull(),
                'situs_jdih' => $this->string(100)->notNull(),
                'pemutakhiran' => $this->string(100)->notNull(),
                'web_yg_dikelola' => $this->string()->notNull(),
                '_created_by' => $this->integer()->notNull(),
                '_updated_by' => $this->integer()->notNull(),
                '_created_time' => $this->dateTime()->notNull(),
                '_updated_time' => $this->dateTime()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%report}}');
    }
}
