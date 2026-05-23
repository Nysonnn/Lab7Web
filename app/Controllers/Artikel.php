<?php

namespace App\Controllers;

use App\Models\ArtikelModel;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';

        $model = new ArtikelModel();
        $artikel = $model->findAll();

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function admin_index()
    {
        $title = 'Daftar Artikel';

        $q = $this->request->getVar('q') ?? '';

        $model = new ArtikelModel();

        $data = [
            'title'   => $title,
            'q'       => $q,
            'artikel' => $model->like('judul', $q)->paginate(10),
            'pager'   => $model->pager,
        ];

        return view('artikel/admin_index', $data);
    }
}