<?php

namespace console\migrations;

use yii\db\Migration;

class m260527_120000_add_dokumen_pembentukan_puu_rbac extends Migration
{
    public function safeUp()
    {
        $routes = [
            '/dokumen-pembentukan-puu/index',
            '/dokumen-pembentukan-puu/create',
            '/dokumen-pembentukan-puu/view',
            '/dokumen-pembentukan-puu/update',
            '/dokumen-pembentukan-puu/delete',
            '/dokumen-pembentukan-puu/inactive',
            '/dokumen-pembentukan-puu/tambah-pengarang',
            '/dokumen-pembentukan-puu/ubah-pengarang',
            '/dokumen-pembentukan-puu/hapus-pengarang',
            '/dokumen-pembentukan-puu/tambah-pengarang2',
            '/dokumen-pembentukan-puu/view-pengarang',
            '/dokumen-pembentukan-puu/tambah-subyek',
            '/dokumen-pembentukan-puu/ubah-subyek',
            '/dokumen-pembentukan-puu/hapus-subyek',
            '/dokumen-pembentukan-puu/tambah-lampiran',
            '/dokumen-pembentukan-puu/ubah-lampiran',
            '/dokumen-pembentukan-puu/hapus-lampiran',
            '/dokumen-pembentukan-puu/tambah-eksemplar',
            '/dokumen-pembentukan-puu/ubah-eksemplar',
            '/dokumen-pembentukan-puu/hapus-eksemplar',
            '/dokumen-pembentukan-puu/cetak',
            '/dokumen-pembentukan-puu/download',
            '/dokumen-pembentukan-puu/download-peraturan',
            '/dokumen-pembentukan-puu/download-abstrak',
            '/dokumen-pembentukan-puu/get-peraturan',
            '/dokumen-pembentukan-puu/*',
        ];

        $time = time();
        foreach ($routes as $route) {
            $this->insert('{{%auth_item}}', [
                'name' => $route,
                'type' => 2,
                'description' => 'Dokumen Penyusunan PUU: ' . $route,
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

        $pustakawanRoutes = [
            '/dokumen-pembentukan-puu/index',
            '/dokumen-pembentukan-puu/create',
            '/dokumen-pembentukan-puu/view',
            '/dokumen-pembentukan-puu/update',
            '/dokumen-pembentukan-puu/inactive',
            '/dokumen-pembentukan-puu/tambah-pengarang',
            '/dokumen-pembentukan-puu/ubah-pengarang',
            '/dokumen-pembentukan-puu/hapus-pengarang',
            '/dokumen-pembentukan-puu/tambah-pengarang2',
            '/dokumen-pembentukan-puu/tambah-subyek',
            '/dokumen-pembentukan-puu/ubah-subyek',
            '/dokumen-pembentukan-puu/hapus-subyek',
            '/dokumen-pembentukan-puu/tambah-lampiran',
            '/dokumen-pembentukan-puu/ubah-lampiran',
            '/dokumen-pembentukan-puu/hapus-lampiran',
            '/dokumen-pembentukan-puu/tambah-eksemplar',
            '/dokumen-pembentukan-puu/ubah-eksemplar',
            '/dokumen-pembentukan-puu/hapus-eksemplar',
            '/dokumen-pembentukan-puu/cetak',
            '/dokumen-pembentukan-puu/download',
            '/dokumen-pembentukan-puu/download-peraturan',
            '/dokumen-pembentukan-puu/download-abstrak',
            '/dokumen-pembentukan-puu/get-peraturan',
        ];

        foreach ($pustakawanRoutes as $route) {
            $this->insert('{{%auth_item_child}}', [
                'parent' => 'pustakawan',
                'child' => $route,
            ]);
        }

        $this->insert('{{%auth_item_child}}', [
            'parent' => 'superadmin',
            'child' => '/dokumen-pembentukan-puu/*',
        ]);

        $this->insert('{{%menu}}', [
            'name' => 'Dokumen Penyusunan PUU',
            'parent' => 16,
            'route' => null,
            'order' => 15,
            'data' => serialize(['fa fa-file-text-o']),
        ]);

        
    }

    public function safeDown()
    {
        $routes = [
            '/dokumen-pembentukan-puu/index',
            '/dokumen-pembentukan-puu/create',
            '/dokumen-pembentukan-puu/view',
            '/dokumen-pembentukan-puu/update',
            '/dokumen-pembentukan-puu/delete',
            '/dokumen-pembentukan-puu/inactive',
            '/dokumen-pembentukan-puu/tambah-pengarang',
            '/dokumen-pembentukan-puu/ubah-pengarang',
            '/dokumen-pembentukan-puu/hapus-pengarang',
            '/dokumen-pembentukan-puu/tambah-pengarang2',
            '/dokumen-pembentukan-puu/view-pengarang',
            '/dokumen-pembentukan-puu/tambah-subyek',
            '/dokumen-pembentukan-puu/ubah-subyek',
            '/dokumen-pembentukan-puu/hapus-subyek',
            '/dokumen-pembentukan-puu/tambah-lampiran',
            '/dokumen-pembentukan-puu/ubah-lampiran',
            '/dokumen-pembentukan-puu/hapus-lampiran',
            '/dokumen-pembentukan-puu/tambah-eksemplar',
            '/dokumen-pembentukan-puu/ubah-eksemplar',
            '/dokumen-pembentukan-puu/hapus-eksemplar',
            '/dokumen-pembentukan-puu/cetak',
            '/dokumen-pembentukan-puu/download',
            '/dokumen-pembentukan-puu/download-peraturan',
            '/dokumen-pembentukan-puu/download-abstrak',
            '/dokumen-pembentukan-puu/get-peraturan',
            '/dokumen-pembentukan-puu/*',
        ];

        foreach ($routes as $route) {
            $this->delete('{{%auth_item_child}}', ['child' => $route]);
        }

        foreach ($routes as $route) {
            $this->delete('{{%auth_item}}', ['name' => $route]);
        }

        $this->delete('{{%menu}}', [
            'name' => 'Dokumen Penyusunan PUU',
            'name' => 'Dokumen Penyusunan PUU',
        ]);

        $this->delete('{{%menu}}', [
            'name' => 'Dokumen Penyusunan PUU',
            'parent' => 16,
        ]);
    }
}