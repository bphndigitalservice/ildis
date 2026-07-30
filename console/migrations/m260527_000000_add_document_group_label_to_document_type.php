<?php

namespace console\migrations;

use yii\db\Migration;
use yii\db\Query;

class m260527_000000_add_document_group_label_to_document_type extends Migration
{
    public function safeUp()
    {
        $table = '{{%document_type}}';
        $schema = $this->db->schema->getTableSchema('document_type', true);

        if ($schema->getColumn('document_group_label') === null) {
            $this->addColumn($table, 'document_group_label',
                $this->string(64)->null()->defaultValue(null)->after('singkatan'));
        }
        $schema = $this->db->schema->getTableSchema('document_type', true);
        if ($schema->getColumn('slug') === null) {
            $this->addColumn($table, 'slug',
                $this->string(128)->null()->defaultValue(null)->after('document_group_label'));
        }

        $indexNames = array_column($this->db->schema->getTableIndexes('document_type', true), 'name');
        if (!in_array('idx_document_group_label', $indexNames, true)) {
            $this->createIndex('idx_document_group_label', $table, 'document_group_label');
        }
        if (!in_array('idx_document_type_slug', $indexNames, true)) {
            $this->createIndex('idx_document_type_slug', $table, 'slug', true);
        }

        $tagged = [
            76 => [
                'name' => 'NASKAH AKADEMIK KEMENKUM',
                'singkatan' => 'NASKAH AKADEMIK KEMENKUM',
                'document_group_label' => 'legislation_formation',
                'slug' => 'naskah-akademik-kemenkum',
            ],
            77 => ['document_group_label' => 'legislation_formation', 'slug' => 'naskah-akademik'],
            78 => ['document_group_label' => 'legislation_formation', 'slug' => 'penelitian-hukum'],
            79 => ['document_group_label' => 'legislation_formation', 'slug' => 'pengkajian-hukum'],
            80 => ['document_group_label' => 'legislation_formation', 'slug' => 'pengkajian-konstitusi'],
            83 => ['document_group_label' => 'legislation_formation', 'slug' => 'analisis-dan-evaluasi'],
            84 => ['document_group_label' => 'legislation_formation', 'slug' => 'rancangan-puu'],
        ];
        foreach ($tagged as $id => $attrs) {
            $this->update($table, $attrs, ['id' => $id]);
        }

        $this->update('{{%document}}',
            ['jenis_peraturan' => 'NASKAH AKADEMIK KEMENKUM'],
            ['jenis_peraturan' => 'NASKAH AKADEMIK KEMENKUMHAM']
        );

        $this->db->createCommand()->update($table,
            [
                'name' => 'RISALAH PEMBAHASAN',
                'singkatan' => 'RISALAH PEMBAHASAN',
                'document_group_label' => 'legislation_formation',
                'slug' => 'risalah-pembahasan',
            ],
            ['id' => 147, 'name' => 'Risalah Rapat']
        )->execute();

        $exists = (new Query())->from($table)
            ->where(['name' => 'PROGRAM PENYUSUNAN PUU'])
            ->exists($this->db);
        if (!$exists) {
            $this->insert($table, [
                'second_id' => '2:148',
                'parent_id' => 2,
                'name' => 'PROGRAM PENYUSUNAN PUU',
                'singkatan' => 'PROGRAM PENYUSUNAN PUU',
                'document_group_label' => 'legislation_formation',
                'slug' => 'program-penyusunan-puu',
                'integrasi' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }

        $permission = '/document-group/legislation-formation';
        $authExists = (new Query())->from('{{%auth_item}}')
            ->where(['name' => $permission])
            ->exists($this->db);
        if (!$authExists) {
            $time = time();
            $this->insert('{{%auth_item}}', [
                'name' => $permission,
                'type' => 2,
                'description' => 'View Dokumen Penyusunan PUU menu group',
                'rule_name' => null,
                'data' => null,
                'created_at' => $time,
                'updated_at' => $time,
            ]);
        }

        foreach (['pustakawan', 'superadmin'] as $roleName) {
            $childExists = (new Query())->from('{{%auth_item_child}}')
                ->where(['parent' => $roleName, 'child' => $permission])
                ->exists($this->db);
            if (!$childExists) {
                $this->insert('{{%auth_item_child}}', [
                    'parent' => $roleName,
                    'child' => $permission,
                ]);
            }
        }
    }

    public function safeDown()
    {
        $permission = '/document-group/legislation-formation';

        $this->delete('{{%auth_item_child}}', ['child' => $permission]);
        $this->delete('{{%auth_item}}', ['name' => $permission]);

        $this->delete('{{%document_type}}', ['name' => 'PROGRAM PENYUSUNAN PUU']);

        $this->update('{{%document_type}}',
            [
                'name' => 'Risalah Rapat',
                'singkatan' => 'Risalah Rapat',
                'document_group_label' => null,
                'slug' => null,
            ],
            ['id' => 147]
        );

        $this->update('{{%document}}',
            ['jenis_peraturan' => 'NASKAH AKADEMIK KEMENKUMHAM'],
            ['jenis_peraturan' => 'NASKAH AKADEMIK KEMENKUM']
        );

        $this->update('{{%document_type}}',
            [
                'name' => 'NASKAH AKADEMIK KEMENKUMHAM',
                'singkatan' => 'NASKAH AKADEMIK KEMENKUMHAM',
                'document_group_label' => null,
                'slug' => null,
            ],
            ['id' => 76]
        );

        $this->update('{{%document_type}}',
            ['document_group_label' => null, 'slug' => null],
            ['id' => [77, 78, 79, 80, 83, 84]]
        );

        $this->dropIndex('idx_document_type_slug', '{{%document_type}}');
        $this->dropIndex('idx_document_group_label', '{{%document_type}}');
        $this->dropColumn('{{%document_type}}', 'slug');
        $this->dropColumn('{{%document_type}}', 'document_group_label');
    }
}
