<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriRelation extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('kategori')) {
            $this->forge->addField([
                'id_kategori' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama_kategori' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'slug_kategori' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
            ]);
            $this->forge->addKey('id_kategori', true);
            $this->forge->addUniqueKey('slug_kategori');
            $this->forge->createTable('kategori');
        }

        if (! $this->db->fieldExists('id_kategori', 'artikel')) {
            $this->forge->addColumn('artikel', [
                'id_kategori' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ]);
        }

        $constraint = $this->db->query(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ?
             AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
            [$this->db->getDatabase(), 'artikel', 'fk_kategori_artikel', 'FOREIGN KEY']
        )->getRowArray();

        if ($constraint === null) {
            $this->db->query(
                'ALTER TABLE artikel ADD CONSTRAINT fk_kategori_artikel
                 FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
                 ON UPDATE CASCADE ON DELETE SET NULL'
            );
        }
    }

    public function down()
    {
        $constraint = $this->db->query(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ?
             AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
            [$this->db->getDatabase(), 'artikel', 'fk_kategori_artikel', 'FOREIGN KEY']
        )->getRowArray();

        if ($constraint !== null) {
            $this->db->query('ALTER TABLE artikel DROP FOREIGN KEY fk_kategori_artikel');
        }

        if ($this->db->fieldExists('id_kategori', 'artikel')) {
            $this->forge->dropColumn('artikel', 'id_kategori');
        }

        $this->forge->dropTable('kategori', true);
    }
}
