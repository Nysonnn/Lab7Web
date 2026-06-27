<?= $this->include('template/header'); ?>

<article class="article-detail">
    <a class="back-link" href="<?= base_url('/artikel'); ?>">&larr; Kembali ke daftar artikel</a>
    <span class="badge"><?= esc($artikel['nama_kategori'] ?? 'Tanpa kategori'); ?></span>
    <h2><?= esc($artikel['judul']); ?></h2>
    <p class="article-meta">Status: <?= (int) $artikel['status'] === 1 ? 'Terbit' : 'Draft'; ?></p>
    <?php if (! empty($artikel['gambar'])): ?>
        <img src="<?= base_url('/gambar/' . $artikel['gambar']); ?>" alt="<?= esc($artikel['judul']); ?>">
    <?php endif; ?>
    <div class="article-body"><?= nl2br(esc($artikel['isi'])); ?></div>
</article>

<?= $this->include('template/footer'); ?>
