<?= $this->include('template/header'); ?>

<div class="page-heading">
    <div>
        <p class="eyebrow">Dashboard Admin</p>
        <h2><?= esc($title); ?></h2>
    </div>
    <a href="<?= base_url('/admin/artikel/add'); ?>" class="btn btn-primary">+ Tambah Artikel</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')); ?></div>
<?php endif; ?>

<form method="get" class="filter-form">
    <div class="field grow">
        <label for="q">Cari judul</label>
        <input type="search" id="q" name="q" value="<?= esc($q); ?>" placeholder="Masukkan judul artikel">
    </div>
    <div class="field">
        <label for="kategori_id">Kategori</label>
        <select name="kategori_id" id="kategori_id">
            <option value="">Semua Kategori</option>
            <?php foreach ($kategori as $item): ?>
                <option value="<?= $item['id_kategori']; ?>"
                    <?= (string) $kategori_id === (string) $item['id_kategori'] ? 'selected' : ''; ?>>
                    <?= esc($item['nama_kategori']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Terapkan</button>
    <?php if ($q !== '' || $kategori_id !== ''): ?>
        <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-light">Reset</a>
    <?php endif; ?>
</form>

<div class="table-wrap">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($artikel !== []): ?>
            <?php foreach ($artikel as $row): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td>
                        <strong><?= esc($row['judul']); ?></strong>
                        <small><?= esc(mb_strimwidth(strip_tags($row['isi']), 0, 72, '...')); ?></small>
                    </td>
                    <td><span class="badge"><?= esc($row['nama_kategori'] ?? 'Tanpa kategori'); ?></span></td>
                    <td>
                        <span class="status <?= (int) $row['status'] === 1 ? 'published' : 'draft'; ?>">
                            <?= (int) $row['status'] === 1 ? 'Terbit' : 'Draft'; ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a class="btn btn-small btn-info" href="<?= base_url('/admin/artikel/edit/' . $row['id']); ?>">Ubah</a>
                        <a class="btn btn-small btn-danger" onclick="return confirm('Yakin menghapus data?');"
                           href="<?= base_url('/admin/artikel/delete/' . $row['id']); ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="empty-cell">Tidak ada data yang sesuai.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    <?= $pager->only(['q', 'kategori_id'])->links('artikel'); ?>
</div>

<?= $this->include('template/footer'); ?>
