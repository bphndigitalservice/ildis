<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Dokumen;

/**
 * DokumenSearch represents the model behind the search form of `frontend\models\Dokumen`.
 */
class DokumenSearch extends Dokumen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'tipe_dokumen', 'daerah', 'hit_see', 'hit_download'], 'integer'],
            [['judul', 'teu', 'nomor_peraturan', 'nomor_panggil', 'bentuk_peraturan', 'jenis_peraturan', 'singkatan_jenis', 'cetakan', 'tempat_terbit', 'penerbit', 'tanggal_penetapan', 'deskripsi_fisik', 'sumber', 'isbn', 'bahasa', 'bidang_hukum', 'nomor_induk_buku', 'singkatan_bentuk', 'tipe_koleksi_nomor_eksemplar', 'pola_nomor_eksemplar', 'jumlah_eksemplar', 'kala_terbit', 'tahun_terbit', 'tanggal_dibacakan', 'pernyataan_tanggung_jawab', 'edisi', 'gmd', 'judul_seri', 'klasifikasi', 'info_detil_spesifik', 'abstrak', 'gambar_sampul', 'label', 'sembunyikan_di_opac', 'promosikan_ke_beranda', 'status_terakhir', 'status', 'integrasi', '_created_by', '_updated_by', 'created_at', 'updated_at', 'inisiatif', 'pemrakarsa', 'tanggal_pengundangan', 'penandatanganan', 'lembaga_peradilan', 'pemohon', 'termohon', 'jenis_perkara', 'sub_klasifikasi', 'amar_status', 'berkekuatan_hukum_tetap', 'urusan_pemerintahan', 'catatan_status_peraturan'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Dokumen::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tipe_dokumen' => $this->tipe_dokumen,
            'tanggal_penetapan' => $this->tanggal_penetapan,
            'tanggal_dibacakan' => $this->tanggal_dibacakan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tanggal_pengundangan' => $this->tanggal_pengundangan,
            'daerah' => $this->daerah,
            'hit_see' => $this->hit_see,
            'hit_download' => $this->hit_download,
        ]);

        $query->andFilterWhere(['like', 'judul', $this->judul])
            ->andFilterWhere(['like', 'teu', $this->teu])
            ->andFilterWhere(['like', 'nomor_peraturan', $this->nomor_peraturan])
            ->andFilterWhere(['like', 'nomor_panggil', $this->nomor_panggil])
            ->andFilterWhere(['like', 'bentuk_peraturan', $this->bentuk_peraturan])
            ->andFilterWhere(['like', 'jenis_peraturan', $this->jenis_peraturan])
            ->andFilterWhere(['like', 'singkatan_jenis', $this->singkatan_jenis])
            ->andFilterWhere(['like', 'cetakan', $this->cetakan])
            ->andFilterWhere(['like', 'tempat_terbit', $this->tempat_terbit])
            ->andFilterWhere(['like', 'penerbit', $this->penerbit])
            ->andFilterWhere(['like', 'deskripsi_fisik', $this->deskripsi_fisik])
            ->andFilterWhere(['like', 'sumber', $this->sumber])
            ->andFilterWhere(['like', 'isbn', $this->isbn])
            ->andFilterWhere(['like', 'bahasa', $this->bahasa])
            ->andFilterWhere(['like', 'bidang_hukum', $this->bidang_hukum])
            ->andFilterWhere(['like', 'nomor_induk_buku', $this->nomor_induk_buku])
            ->andFilterWhere(['like', 'singkatan_bentuk', $this->singkatan_bentuk])
            ->andFilterWhere(['like', 'tipe_koleksi_nomor_eksemplar', $this->tipe_koleksi_nomor_eksemplar])
            ->andFilterWhere(['like', 'pola_nomor_eksemplar', $this->pola_nomor_eksemplar])
            ->andFilterWhere(['like', 'jumlah_eksemplar', $this->jumlah_eksemplar])
            ->andFilterWhere(['like', 'kala_terbit', $this->kala_terbit])
            ->andFilterWhere(['like', 'tahun_terbit', $this->tahun_terbit])
            ->andFilterWhere(['like', 'pernyataan_tanggung_jawab', $this->pernyataan_tanggung_jawab])
            ->andFilterWhere(['like', 'edisi', $this->edisi])
            ->andFilterWhere(['like', 'gmd', $this->gmd])
            ->andFilterWhere(['like', 'judul_seri', $this->judul_seri])
            ->andFilterWhere(['like', 'klasifikasi', $this->klasifikasi])
            ->andFilterWhere(['like', 'info_detil_spesifik', $this->info_detil_spesifik])
            ->andFilterWhere(['like', 'abstrak', $this->abstrak])
            ->andFilterWhere(['like', 'gambar_sampul', $this->gambar_sampul])
            ->andFilterWhere(['like', 'label', $this->label])
            ->andFilterWhere(['like', 'sembunyikan_di_opac', $this->sembunyikan_di_opac])
            ->andFilterWhere(['like', 'promosikan_ke_beranda', $this->promosikan_ke_beranda])
            ->andFilterWhere(['like', 'status_terakhir', $this->status_terakhir])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'integrasi', $this->integrasi])
            ->andFilterWhere(['like', '_created_by', $this->_created_by])
            ->andFilterWhere(['like', '_updated_by', $this->_updated_by])
            ->andFilterWhere(['like', 'inisiatif', $this->inisiatif])
            ->andFilterWhere(['like', 'pemrakarsa', $this->pemrakarsa])
            ->andFilterWhere(['like', 'penandatanganan', $this->penandatanganan])
            ->andFilterWhere(['like', 'lembaga_peradilan', $this->lembaga_peradilan])
            ->andFilterWhere(['like', 'pemohon', $this->pemohon])
            ->andFilterWhere(['like', 'termohon', $this->termohon])
            ->andFilterWhere(['like', 'jenis_perkara', $this->jenis_perkara])
            ->andFilterWhere(['like', 'sub_klasifikasi', $this->sub_klasifikasi])
            ->andFilterWhere(['like', 'amar_status', $this->amar_status])
            ->andFilterWhere(['like', 'berkekuatan_hukum_tetap', $this->berkekuatan_hukum_tetap])
            ->andFilterWhere(['like', 'urusan_pemerintahan', $this->urusan_pemerintahan])
            ->andFilterWhere(['like', 'catatan_status_peraturan', $this->catatan_status_peraturan]);

        return $dataProvider;
    }
}
