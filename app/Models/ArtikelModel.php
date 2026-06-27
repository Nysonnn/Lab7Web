<?php

namespace App\Models;

use CodeIgniter\Model;

class ArtikelModel extends Model
{
    protected $table = 'artikel';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'judul',
        'isi',
        'status',
        'slug',
        'gambar',
        'id_kategori',
    ];

    public function getArtikelDenganKategori(?string $slugKategori = null): array
    {
        $this->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC');

        if ($slugKategori !== null) {
            $this->where('kategori.slug_kategori', $slugKategori);
        }

        return $this->findAll();
    }

    public function getArtikelDenganKategoriBySlug(string $slug): ?array
    {
        return $this->select('artikel.*, kategori.nama_kategori, kategori.slug_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->where('artikel.slug', $slug)
            ->first();
    }
}
