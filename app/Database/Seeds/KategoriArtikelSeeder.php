<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriArtikelSeeder extends Seeder
{
    public function run()
    {
        helper('url');

        $kategoriBuilder = $this->db->table('kategori');
        $kategoriData = [
            ['nama_kategori' => 'Teknologi', 'slug_kategori' => 'teknologi'],
            ['nama_kategori' => 'Pemrograman', 'slug_kategori' => 'pemrograman'],
            ['nama_kategori' => 'Pendidikan', 'slug_kategori' => 'pendidikan'],
        ];

        foreach ($kategoriData as $kategori) {
            $exists = $kategoriBuilder
                ->where('slug_kategori', $kategori['slug_kategori'])
                ->countAllResults() > 0;

            if (! $exists) {
                $kategoriBuilder->insert($kategori);
            }
        }

        $kategori = [];
        foreach ($kategoriBuilder->get()->getResultArray() as $row) {
            $kategori[$row['slug_kategori']] = (int) $row['id_kategori'];
        }

        $artikelBuilder = $this->db->table('artikel');

        if ($artikelBuilder->countAllResults() === 0) {
            $artikel = [
                ['Mengenal CodeIgniter 4', 'Pengenalan framework CodeIgniter 4 dan struktur proyeknya.', 'pemrograman'],
                ['Relasi Tabel One-to-Many', 'Memahami hubungan satu kategori dengan banyak artikel.', 'pemrograman'],
                ['Query Builder CodeIgniter', 'Membuat query database yang aman dan mudah dibaca.', 'pemrograman'],
                ['Perkembangan Teknologi Web', 'Ringkasan perkembangan teknologi web modern.', 'teknologi'],
                ['Mengenal PHP 8', 'Fitur penting PHP 8 untuk pengembangan aplikasi web.', 'teknologi'],
                ['Belajar Database MySQL', 'Konsep dasar pengelolaan data menggunakan MySQL.', 'pendidikan'],
                ['Pentingnya Literasi Digital', 'Literasi digital membantu masyarakat menggunakan teknologi dengan bijak.', 'pendidikan'],
                ['Membuat Model pada CI4', 'Model menghubungkan aplikasi dengan tabel di database.', 'pemrograman'],
                ['Membuat Controller pada CI4', 'Controller mengatur alur request dan response aplikasi.', 'pemrograman'],
                ['Menyusun View yang Rapi', 'View menampilkan data kepada pengguna dengan struktur yang jelas.', 'pemrograman'],
                ['Keamanan Aplikasi Web', 'Prinsip dasar menjaga keamanan aplikasi berbasis web.', 'teknologi'],
                ['Pemanfaatan Teknologi di Kelas', 'Teknologi dapat mendukung proses belajar yang interaktif.', 'pendidikan'],
            ];

            foreach ($artikel as $index => [$judul, $isi, $slugKategori]) {
                $artikelBuilder->insert([
                    'judul'       => $judul,
                    'isi'         => $isi,
                    'gambar'      => null,
                    'status'      => $index % 4 === 0 ? 0 : 1,
                    'slug'        => url_title($judul, '-', true),
                    'id_kategori' => $kategori[$slugKategori],
                ]);
            }
        } else {
            $kategoriIds = array_values($kategori);
            $tanpaKategori = $artikelBuilder
                ->where('id_kategori', null)
                ->get()
                ->getResultArray();

            foreach ($tanpaKategori as $index => $row) {
                $artikelBuilder
                    ->where('id', $row['id'])
                    ->update(['id_kategori' => $kategoriIds[$index % count($kategoriIds)]]);
            }
        }
    }
}
