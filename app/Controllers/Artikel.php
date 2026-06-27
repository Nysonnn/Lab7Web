<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;
use Throwable;

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
        $page = max(1, (int) ($this->request->getGet('page') ?? $this->request->getGet('page_artikel') ?? 1));
        $sort = (string) ($this->request->getGet('sort') ?? 'id');
        $direction = strtoupper((string) ($this->request->getGet('direction') ?? 'DESC'));

        $sortableColumns = [
            'id'       => 'artikel.id',
            'judul'    => 'artikel.judul',
            'kategori' => 'kategori.nama_kategori',
            'status'   => 'artikel.status',
        ];

        if (! array_key_exists($sort, $sortableColumns)) {
            $sort = 'id';
        }

        if (! in_array($direction, ['ASC', 'DESC'], true)) {
            $direction = 'DESC';
        }

        $model = new ArtikelModel();
        $model->select('artikel.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = artikel.id_kategori', 'left')
            ->orderBy($sortableColumns[$sort], $direction);

        if ($q !== '') {
            $model->like('artikel.judul', $q);
        }

        if ($kategoriId !== '') {
            $model->where('artikel.id_kategori', $kategoriId);
        }

        $artikel = $model->paginate(10, 'artikel', $page);
        $pager = $model->pager;

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'OK',
                'artikel' => $artikel,
                'filters' => [
                    'q'           => $q,
                    'kategori_id' => $kategoriId,
                    'sort'        => $sort,
                    'direction'   => $direction,
                ],
                'pager' => [
                    'currentPage' => $pager->getCurrentPage('artikel'),
                    'pageCount'   => $pager->getPageCount('artikel'),
                    'perPage'     => $pager->getPerPage('artikel'),
                    'total'       => $pager->getTotal('artikel'),
                ],
            ]);
        }

        return view('artikel/admin_index', [
            'title'        => 'Daftar Artikel (Admin)',
            'q'            => $q,
            'kategori_id'  => $kategoriId,
            'sort'         => $sort,
            'direction'    => $direction,
            'artikel'      => $artikel,
            'pager'        => $pager,
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
            'gambar'      => 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif,image/webp]|max_size[gambar,2048]',
        ];

        if (! $this->validate($rules)) {
            $data['errors'] = $this->validator->getErrors();
            $data['input'] = $this->request->getPost();

            return view('artikel/form_add', $data);
        }

        $judul = trim((string) $this->request->getPost('judul'));
        $fileName = $this->storeImage($this->request->getFile('gambar'));

        try {
            (new ArtikelModel())->insert([
                'judul'       => $judul,
                'isi'         => trim((string) $this->request->getPost('isi')),
                'slug'        => url_title($judul, '-', true),
                'status'      => (int) ($this->request->getPost('status') ?? 0),
                'id_kategori' => (int) $this->request->getPost('id_kategori'),
                'gambar'      => $fileName,
            ]);
        } catch (Throwable $exception) {
            $this->deleteImage($fileName);
            throw $exception;
        }

        return redirect()->to('/admin/artikel')->with('success', 'Artikel dan gambar berhasil ditambahkan.');
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

        $gambarBaru = $this->request->getFile('gambar');
        $hasNewImage = $gambarBaru !== null && $gambarBaru->getError() !== UPLOAD_ERR_NO_FILE;

        if ($hasNewImage) {
            $rules['gambar'] = 'uploaded[gambar]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif,image/webp]|max_size[gambar,2048]';
        }

        if (! $this->validate($rules)) {
            $data['errors'] = $this->validator->getErrors();
            $data['input'] = $this->request->getPost();

            return view('artikel/form_edit', $data);
        }

        $judul = trim((string) $this->request->getPost('judul'));
        $updateData = [
            'judul'       => $judul,
            'isi'         => trim((string) $this->request->getPost('isi')),
            'slug'        => url_title($judul, '-', true),
            'status'      => (int) ($this->request->getPost('status') ?? 0),
            'id_kategori' => (int) $this->request->getPost('id_kategori'),
        ];

        $fileName = null;
        if ($hasNewImage && $gambarBaru instanceof UploadedFile) {
            $fileName = $this->storeImage($gambarBaru);
            $updateData['gambar'] = $fileName;
        }

        try {
            $model->update($id, $updateData);
        } catch (Throwable $exception) {
            if ($fileName !== null) {
                $this->deleteImage($fileName);
            }
            throw $exception;
        }

        if ($fileName !== null) {
            $this->deleteImage($artikel['gambar'] ?? null);
        }

        return redirect()->to('/admin/artikel')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        $model = new ArtikelModel();

        $artikel = $model->find($id);

        if ($artikel === null) {
            throw PageNotFoundException::forPageNotFound('Artikel tidak ditemukan.');
        }

        $model->delete($id);
        $this->deleteImage($artikel['gambar'] ?? null);

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

    private function storeImage(?UploadedFile $file): string
    {
        if ($file === null || ! $file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('File gambar tidak valid atau sudah dipindahkan.');
        }

        $directory = FCPATH . 'gambar';
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException('Folder upload gambar tidak dapat dibuat.');
        }

        $fileName = $file->getRandomName();
        $file->move($directory, $fileName);

        return $fileName;
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
