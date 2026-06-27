<?= $this->include('template/header'); ?>

<div class="page-heading">
    <div>
        <p class="eyebrow">Artikel</p>
        <h2><?= esc($title); ?></h2>
    </div>
</div>

<nav class="category-filter" aria-label="Filter kategori">
    <a class="category-chip <?= $kategoriAktif === null ? 'active' : ''; ?>"
       href="<?= base_url('/artikel'); ?>">Semua</a>
    <?php foreach ($kategori as $item): ?>
        <a class="category-chip <?= ($kategoriAktif['id_kategori'] ?? null) === $item['id_kategori'] ? 'active' : ''; ?>"
           href="<?= base_url('/kategori/' . $item['slug_kategori']); ?>">
            <?= esc($item['nama_kategori']); ?>
        </a>
    <?php endforeach; ?>
</nav>

<?php if ($artikel !== []): ?>
    <div class="article-list">
        <?php foreach ($artikel as $row): ?>
            <article class="entry">
                <span class="badge"><?= esc($row['nama_kategori'] ?? 'Tanpa kategori'); ?></span>
                <h3>
                    <a href="<?= base_url('/artikel/' . $row['slug']); ?>">
                        <?= esc($row['judul']); ?>
                    </a>
                </h3>
                <?php if (! empty($row['gambar'])): ?>
                    <img src="<?= base_url('/gambar/' . $row['gambar']); ?>" alt="<?= esc($row['judul']); ?>">
                <?php endif; ?>
                <p><?= esc(mb_strimwidth(strip_tags($row['isi']), 0, 180, '...')); ?></p>
                <a class="read-more" href="<?= base_url('/artikel/' . $row['slug']); ?>">Baca selengkapnya &rarr;</a>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <h3>Belum ada artikel</h3>
        <p>Artikel pada kategori ini belum tersedia.</p>
    </div>
<?php endif; ?>

<?= $this->include('template/footer'); ?>
