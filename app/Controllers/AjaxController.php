<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;

class AjaxController extends BaseController
{
    public function index()
    {
        return view('ajax/index', [
            'title'    => 'Data Artikel AJAX',
            'kategori' => (new KategoriModel())->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function getData()
    {
        $data = (new ArtikelModel())
            ->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => 'OK',
            'data'   => $data,
        ]);
    }

    public function create()
    {
        helper('url');
        $input = $this->requestData();
        $errors = $this->validateArticle($input);

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Data artikel belum valid.',
                'errors'  => $errors,
            ]);
        }

        $judul = trim((string) $input['judul']);
        $model = new ArtikelModel();
        $model->insert([
            'judul'       => $judul,
            'isi'         => trim((string) $input['isi']),
            'slug'        => url_title($judul, '-', true),
            'status'      => (int) ($input['status'] ?? 0),
            'id_kategori' => (int) $input['id_kategori'],
        ]);

        return $this->response->setStatusCode(201)->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil ditambahkan tanpa reload halaman.',
            'data'    => $model->find($model->getInsertID()),
        ]);
    }

    public function update(int $id)
    {
        helper('url');
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if ($artikel === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Artikel tidak ditemukan.',
            ]);
        }

        $input = $this->requestData();
        $errors = $this->validateArticle($input);

        if ($errors !== []) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Data artikel belum valid.',
                'errors'  => $errors,
            ]);
        }

        $judul = trim((string) $input['judul']);
        $model->update($id, [
            'judul'       => $judul,
            'isi'         => trim((string) $input['isi']),
            'slug'        => url_title($judul, '-', true),
            'status'      => (int) ($input['status'] ?? 0),
            'id_kategori' => (int) $input['id_kategori'],
        ]);

        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil diperbarui tanpa reload halaman.',
            'data'    => $model->find($id),
        ]);
    }

    public function delete(int $id)
    {
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if ($artikel === null) {
            return $this->response->setStatusCode(404)->setJSON([
                'status'  => 'ERROR',
                'message' => 'Artikel tidak ditemukan.',
            ]);
        }

        $model->delete($id);
        $this->deleteImage($artikel['gambar'] ?? null);

        return $this->response->setJSON([
            'status'  => 'OK',
            'message' => 'Artikel berhasil dihapus tanpa reload halaman.',
        ]);
    }

    private function requestData(): array
    {
        if (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
            $json = $this->request->getJSON(true);
            if (is_array($json)) {
                return $json;
            }
        }

        if ($this->request->is('put')) {
            return $this->request->getRawInput();
        }

        return $this->request->getPost();
    }

    private function validateArticle(array $input): array
    {
        $validation = service('validation');
        $validation->reset();
        $validation->setRules([
            'judul'       => 'required|min_length[3]|max_length[200]',
            'isi'         => 'required',
            'id_kategori' => 'required|is_natural_no_zero|is_not_unique[kategori.id_kategori]',
            'status'      => 'required|in_list[0,1]',
        ]);

        if (! $validation->run($input)) {
            return $validation->getErrors();
        }

        return [];
    }

    private function deleteImage(?string $fileName): void
    {
        if ($fileName === null || $fileName === '') {
            return;
        }

        $path = FCPATH . 'gambar' . DIRECTORY_SEPARATOR . basename($fileName);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
