<?= $this->include('template/header'); ?>

<div class="page-heading">
    <div><p class="eyebrow">Dashboard Admin</p><h2><?= esc($title); ?></h2></div>
</div>

<?php if ($errors !== []): ?>
    <div class="alert alert-danger">
        <strong>Periksa kembali data berikut:</strong>
        <ul><?php foreach ($errors as $error): ?><li><?= esc($error); ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<?php
$judul = $input['judul'] ?? $artikel['judul'];
$isi = $input['isi'] ?? $artikel['isi'];
$kategoriTerpilih = $input['id_kategori'] ?? $artikel['id_kategori'];
$status = $input['status'] ?? $artikel['status'];
?>

<form action="" method="post" class="article-form">
    <?= csrf_field(); ?>
    <div class="field">
        <label for="judul">Judul</label>
        <input type="text" name="judul" id="judul" value="<?= esc($judul); ?>" required>
    </div>
    <div class="field">
        <label for="isi">Isi</label>
        <textarea name="isi" id="isi" rows="9" required><?= esc($isi); ?></textarea>
    </div>
    <div class="form-grid">
        <div class="field">
            <label for="id_kategori">Kategori</label>
            <select name="id_kategori" id="id_kategori" required>
                <?php foreach ($kategori as $item): ?>
                    <option value="<?= $item['id_kategori']; ?>"
                        <?= (string) $kategoriTerpilih === (string) $item['id_kategori'] ? 'selected' : ''; ?>>
                        <?= esc($item['nama_kategori']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="0" <?= (string) $status === '0' ? 'selected' : ''; ?>>Draft</option>
                <option value="1" <?= (string) $status === '1' ? 'selected' : ''; ?>>Terbit</option>
            </select>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="<?= base_url('/admin/artikel'); ?>" class="btn btn-light">Batal</a>
    </div>
</form>

<?= $this->include('template/footer'); ?>
