<?= $this->include('template/header'); ?>

<h1><?= $title; ?></h1>

<hr>

<form method="get" class="form-search">

    <input type="text"
           name="q"
           value="<?= $q; ?>"
           placeholder="Cari data">

    <input type="submit"
           value="Cari"
           class="btn btn-primary">

</form>

<table border="1" cellpadding="10" cellspacing="0">

    <tr>
        <th>ID</th>
        <th>Judul</th>
        <th>Aksi</th>
    </tr>

<?php if($artikel): foreach($artikel as $row): ?>

    <tr>

        <td><?= $row['id']; ?></td>

        <td>
            <?= $row['judul']; ?>
        </td>

        <td>

            <a href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">
                Ubah
            </a>

            |

            <a href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>"
               onclick="return confirm('Yakin menghapus data?');">

               Hapus
            </a>

        </td>

    </tr>

<?php endforeach; else: ?>

    <tr>
        <td colspan="3">Belum ada data.</td>
    </tr>

<?php endif; ?>

</table>

<br>

<?= $pager->only(['q'])->links(); ?>

<?= $this->include('template/footer'); ?>