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

<form method="get" class="filter-form ajax-filter" id="search-form">
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
    <div class="field">
        <label for="sort">Urutkan</label>
        <select name="sort" id="sort">
            <option value="id" <?= $sort === 'id' ? 'selected' : ''; ?>>ID</option>
            <option value="judul" <?= $sort === 'judul' ? 'selected' : ''; ?>>Judul</option>
            <option value="kategori" <?= $sort === 'kategori' ? 'selected' : ''; ?>>Kategori</option>
            <option value="status" <?= $sort === 'status' ? 'selected' : ''; ?>>Status</option>
        </select>
    </div>
    <div class="field">
        <label for="direction">Arah</label>
        <select name="direction" id="direction">
            <option value="ASC" <?= $direction === 'ASC' ? 'selected' : ''; ?>>A–Z / Kecil–Besar</option>
            <option value="DESC" <?= $direction === 'DESC' ? 'selected' : ''; ?>>Z–A / Besar–Kecil</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Terapkan</button>
    <button type="button" class="btn btn-light" id="reset-filter">Reset</button>
</form>

<div class="ajax-status" id="ajax-status" role="status" aria-live="polite" hidden>
    <span class="loading-spinner" aria-hidden="true"></span>
    <span>Memuat data artikel...</span>
</div>

<div class="table-wrap" id="article-container" aria-busy="false">
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
        <tbody id="article-body">
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

<div class="pagination-wrap ajax-pagination" id="pagination-container">
    <?= $pager->only(['q', 'kategori_id', 'sort', 'direction'])->links('artikel'); ?>
</div>

<script src="<?= base_url('/assets/js/jquery-3.6.0.min.js'); ?>"></script>
<script>
$(function () {
    const endpoint = <?= json_encode(base_url('/admin/artikel')); ?>;
    const editBaseUrl = <?= json_encode(base_url('/admin/artikel/edit')); ?>;
    const deleteBaseUrl = <?= json_encode(base_url('/admin/artikel/delete')); ?>;
    let currentPage = <?= (int) $pager->getCurrentPage('artikel'); ?>;
    let searchTimer;

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : String(value)).html();
    }

    function excerpt(value) {
        const text = new DOMParser().parseFromString(value || '', 'text/html').body.textContent || '';
        return text.length > 72 ? text.slice(0, 72) + '...' : text;
    }

    function queryData(page) {
        return {
            q: $('#q').val().trim(),
            kategori_id: $('#kategori_id').val(),
            sort: $('#sort').val(),
            direction: $('#direction').val(),
            page: page
        };
    }

    function renderArticles(items) {
        if (!items.length) {
            $('#article-body').html('<tr><td colspan="5" class="empty-cell">Tidak ada data yang sesuai.</td></tr>');
            return;
        }

        const rows = items.map(function (item) {
            const statusClass = Number(item.status) === 1 ? 'published' : 'draft';
            const statusText = Number(item.status) === 1 ? 'Terbit' : 'Draft';
            const category = item.nama_kategori || 'Tanpa kategori';
            const id = Number(item.id);

            return `<tr>
                <td>${id}</td>
                <td><strong>${escapeHtml(item.judul)}</strong><small>${escapeHtml(excerpt(item.isi))}</small></td>
                <td><span class="badge">${escapeHtml(category)}</span></td>
                <td><span class="status ${statusClass}">${statusText}</span></td>
                <td class="actions">
                    <a class="btn btn-small btn-info" href="${editBaseUrl}/${id}">Ubah</a>
                    <a class="btn btn-small btn-danger delete-link" href="${deleteBaseUrl}/${id}">Hapus</a>
                </td>
            </tr>`;
        });

        $('#article-body').html(rows.join(''));
    }

    function renderPagination(pager) {
        const pageCount = Number(pager.pageCount) || 1;
        const page = Number(pager.currentPage) || 1;
        const total = Number(pager.total) || 0;
        let buttons = `<span class="pagination-summary">${total} artikel · Halaman ${page} dari ${pageCount}</span>`;

        buttons += `<button type="button" class="page-link" data-page="${page - 1}" ${page <= 1 ? 'disabled' : ''}>‹ Sebelumnya</button>`;

        for (let number = 1; number <= pageCount; number++) {
            if (number === 1 || number === pageCount || Math.abs(number - page) <= 1) {
                buttons += `<button type="button" class="page-link ${number === page ? 'active' : ''}" data-page="${number}">${number}</button>`;
            } else if (number === 2 || number === pageCount - 1) {
                buttons += '<span class="page-ellipsis">…</span>';
            }
        }

        buttons += `<button type="button" class="page-link" data-page="${page + 1}" ${page >= pageCount ? 'disabled' : ''}>Berikutnya ›</button>`;
        $('#pagination-container').html(buttons);
    }

    function updateUrl(data) {
        const url = new URL(endpoint, window.location.origin);
        Object.entries(data).forEach(function ([key, value]) {
            if (value !== '' && !(key === 'page' && Number(value) === 1)) {
                url.searchParams.set(key, value);
            }
        });
        window.history.replaceState({}, '', url);
    }

    function loadArticles(page) {
        const data = queryData(page || 1);
        $('#ajax-status').prop('hidden', false);
        $('#article-container').attr('aria-busy', 'true').addClass('is-loading');

        $.ajax({
            url: endpoint,
            method: 'GET',
            data: data,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function (response) {
            currentPage = Number(response.pager.currentPage) || 1;
            renderArticles(response.artikel || []);
            renderPagination(response.pager || {});
            updateUrl(data);
        }).fail(function () {
            $('#article-body').html('<tr><td colspan="5" class="empty-cell error-text">Data gagal dimuat. Silakan coba lagi.</td></tr>');
            $('#pagination-container').empty();
        }).always(function () {
            $('#ajax-status').prop('hidden', true);
            $('#article-container').attr('aria-busy', 'false').removeClass('is-loading');
        });
    }

    $('#search-form').on('submit', function (event) {
        event.preventDefault();
        loadArticles(1);
    });

    $('#q').on('input', function () {
        window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(function () { loadArticles(1); }, 350);
    });

    $('#kategori_id, #sort, #direction').on('change', function () { loadArticles(1); });

    $('#reset-filter').on('click', function () {
        $('#q, #kategori_id').val('');
        $('#sort').val('id');
        $('#direction').val('DESC');
        loadArticles(1);
    });

    $('#pagination-container').on('click', '.page-link', function () {
        if (!this.disabled) loadArticles(Number($(this).data('page')));
    });

    $('#article-body').on('click', '.delete-link', function (event) {
        if (!window.confirm('Yakin menghapus data?')) event.preventDefault();
    });

    loadArticles(currentPage);
});
</script>

<?= $this->include('template/footer'); ?>
