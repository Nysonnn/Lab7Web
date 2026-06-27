<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\RESTful\ResourceController;

class Post extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $artikel = (new ArtikelModel())
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC')
            ->findAll();

        return $this->respond([
            'status'   => 200,
            'artikel'  => $artikel,
            'kategori' => (new KategoriModel())->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function show($id = null)
    {
        $artikel = (new ArtikelModel())
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->find($id);

        if ($artikel === null) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        return $this->respond(['status' => 200, 'artikel' => $artikel]);
    }

    public function create()
    {
        helper('url');
        $input = $this->payload();
        $errors = $this->validatePayload($input);

        if ($errors !== []) {
            return $this->failValidationErrors($errors);
        }

        $model = new ArtikelModel();
        $id = $model->insert([
            'judul'       => trim((string) $input['judul']),
            'isi'         => trim((string) $input['isi']),
            'slug'        => url_title(trim((string) $input['judul']), '-', true) . '-' . time(),
            'status'      => (int) ($input['status'] ?? 0),
            'id_kategori' => $this->categoryId($input),
        ], true);

        return $this->respondCreated([
            'status'   => 201,
            'error'    => null,
            'messages' => ['success' => 'Data artikel berhasil ditambahkan.'],
            'artikel'  => $model->find($id),
        ]);
    }

    public function update($id = null)
    {
        helper('url');
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if ($artikel === null) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        $input = $this->payload();
        $errors = $this->validatePayload($input);

        if ($errors !== []) {
            return $this->failValidationErrors($errors);
        }

        $judul = trim((string) $input['judul']);
        $model->update($id, [
            'judul'       => $judul,
            'isi'         => trim((string) $input['isi']),
            'slug'        => url_title($judul, '-', true) . '-' . $id,
            'status'      => (int) ($input['status'] ?? 0),
            'id_kategori' => $this->categoryId($input, $artikel['id_kategori'] ?? null),
        ]);

        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => ['success' => 'Data artikel berhasil diubah.'],
            'artikel'  => $model->find($id),
        ]);
    }

    public function delete($id = null)
    {
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if ($artikel === null) {
            return $this->failNotFound('Data artikel tidak ditemukan.');
        }

        $model->delete($id);

        $imagePath = FCPATH . 'gambar' . DIRECTORY_SEPARATOR . basename((string) ($artikel['gambar'] ?? ''));
        if (($artikel['gambar'] ?? '') !== '' && is_file($imagePath)) {
            unlink($imagePath);
        }

        return $this->respondDeleted([
            'status'   => 200,
            'error'    => null,
            'messages' => ['success' => 'Data artikel berhasil dihapus.'],
        ]);
    }

    private function payload(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }

        $input = $this->request->getRawInput();

        return is_array($input) ? $input : [];
    }

    private function validatePayload(array $input): array
    {
        $validation = service('validation');
        $validation->setRules([
            'judul'       => 'required|min_length[3]|max_length[200]',
            'isi'         => 'required|min_length[5]',
            'status'      => 'permit_empty|in_list[0,1]',
            'id_kategori' => 'permit_empty|is_natural_no_zero|is_not_unique[kategori.id_kategori]',
        ]);

        return $validation->run($input) ? [] : $validation->getErrors();
    }

    private function categoryId(array $input, mixed $fallback = null): ?int
    {
        $value = $input['id_kategori'] ?? $fallback;

        return $value === null || $value === '' ? null : (int) $value;
    }
}
