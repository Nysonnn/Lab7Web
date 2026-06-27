<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    public function index()
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();

        return view('artikel/index', [
            'title'      => 'Daftar Artikel',
            'artikel'    => $model->getArtikelDenganKategori(),
            'kategori'   => $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'kategoriAktif' => null,
        ]);
    }

    public function kategori(string $slug)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();
        $kategoriAktif = $kategoriModel->where('slug_kategori', $slug)->first();

        if ($kategoriAktif === null) {
            throw PageNotFoundException::forPageNotFound('Kategori tidak ditemukan.');
        }

        return view('artikel/index', [
            'title'         => 'Artikel Kategori ' . $kategoriAktif['nama_kategori'],
            'artikel'       => $model->getArtikelDenganKategori($slug),
            'kategori'      => $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'kategoriAktif' => $kategoriAktif,
        ]);
    }

    public function admin_index()
    {
        $q = trim((string) ($this->request->getGet('q') ?? ''));
        $kategoriId = trim((string) ($this->request->getGet('kategori_id') ?? ''));

        $model = new ArtikelModel();
        $model->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy('artikel.id', 'DESC');

        if ($q !== '') {
            $model->like('artikel.judul', $q);
        }

        if ($kategoriId !== '') {
            $model->where('artikel.id_kategori', $kategoriId);
        }

        return view('artikel/admin_index', [
            'title'        => 'Daftar Artikel (Admin)',
            'q'            => $q,
            'kategori_id'  => $kategoriId,
            'artikel'      => $model->paginate(10, 'artikel'),
            'pager'        => $model->pager,
            'kategori'     => (new KategoriModel())->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function add()
    {
        helper(['form', 'url']);
        $kategoriModel = new KategoriModel();
        $data = [
            'title'     => 'Tambah Artikel',
            'kategori'  => $kategoriModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'errors'    => [],
            'input'     => [],
        ];

        if (! $this->request->is('post')) {
            return view('artikel/form_add', $data);
        }

        $rules = [
            'judul'       => 'required|min_length[3]|max_length[200]',
            'isi'         => 'required',
            'id_kategori' => 'required|is_natural_no_zero|is_not_unique[kategori.id_kategori]',
            'status'      => 'permit_empty|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            $data['errors'] = $this->validator->getErrors();
            $data['input'] = $this->request->getPost();

            return view('artikel/form_add', $data);
        }

        $judul = trim((string) $this->request->getPost('judul'));
        (new ArtikelModel())->insert([
            'judul'       => $judul,
            'isi'         => trim((string) $this->request->getPost('isi')),
            'slug'        => url_title($judul, '-', true),
            'status'      => (int) ($this->request->getPost('status') ?? 0),
            'id_kategori' => (int) $this->request->getPost('id_kategori'),
        ]);

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        helper(['form', 'url']);
        $model = new ArtikelModel();
        $artikel = $model->find($id);

        if ($artikel === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $data = [
            'title'     => 'Edit Artikel',
            'artikel'   => $artikel,
            'kategori'  => (new KategoriModel())->orderBy('nama_kategori', 'ASC')->findAll(),
            'errors'    => [],
            'input'     => [],
        ];

        if (! $this->request->is('post')) {
            return view('artikel/form_edit', $data);
        }

        $rules = [
            'judul'       => 'required|min_length[3]|max_length[200]',
            'isi'         => 'required',
            'id_kategori' => 'required|is_natural_no_zero|is_not_unique[kategori.id_kategori]',
            'status'      => 'permit_empty|in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            $data['errors'] = $this->validator->getErrors();
            $data['input'] = $this->request->getPost();

            return view('artikel/form_edit', $data);
        }

        $judul = trim((string) $this->request->getPost('judul'));
        $model->update($id, [
            'judul'       => $judul,
            'isi'         => trim((string) $this->request->getPost('isi')),
            'slug'        => url_title($judul, '-', true),
            'status'      => (int) ($this->request->getPost('status') ?? 0),
            'id_kategori' => (int) $this->request->getPost('id_kategori'),
        ]);

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new ArtikelModel();

        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil dihapus.');
    }

    public function view(string $slug)
    {
        $artikel = (new ArtikelModel())->getArtikelDenganKategoriBySlug($slug);

        if ($artikel === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        return view('artikel/detail', [
            'title'   => $artikel['judul'],
            'artikel' => $artikel,
        ]);
    }
}
