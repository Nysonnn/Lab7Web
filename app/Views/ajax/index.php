<?= $this->include('template/header'); ?>

<div class="page-heading">
    <div>
        <p class="eyebrow">Praktikum 8</p>
        <h2><?= esc($title); ?></h2>
        <p class="page-description">Kelola artikel secara asynchronous tanpa memuat ulang halaman.</p>
    </div>
    <button type="button" id="btnTambah" class="btn btn-primary">+ Tambah via AJAX</button>
</div>

<div id="ajaxMessage" class="ajax-message" role="status" aria-live="polite" hidden></div>

<section id="ajaxFormPanel" class="ajax-form-panel" hidden>
    <h3 id="formTitle">Tambah Artikel</h3>
    <form id="ajaxArtikelForm">
        <input type="hidden" id="artikelId" name="id">

        <div class="field">
            <label for="ajaxJudul">Judul</label>
            <input type="text" id="ajaxJudul" name="judul" required>
        </div>

        <div class="field">
            <label for="ajaxIsi">Isi</label>
            <textarea id="ajaxIsi" name="isi" rows="5" required></textarea>
        </div>

        <div class="form-grid">
            <div class="field">
                <label for="ajaxKategori">Kategori</label>
                <select id="ajaxKategori" name="id_kategori" required>
                    <option value="">Pilih kategori</option>
                    <?php foreach ($kategori as $item): ?>
                        <option value="<?= $item['id_kategori']; ?>"><?= esc($item['nama_kategori']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label for="ajaxStatus">Status</label>
                <select id="ajaxStatus" name="status" required>
                    <option value="0">Draft</option>
                    <option value="1">Terbit</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan via AJAX</button>
            <button type="button" id="btnBatal" class="btn btn-light">Batal</button>
        </div>
    </form>
</section>

<div class="table-wrap">
    <table class="data-table" id="artikelTable">
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
            <tr><td colspan="5" class="empty-cell">Memuat data...</td></tr>
        </tbody>
    </table>
</div>

<script src="<?= base_url('assets/js/jquery-3.6.0.min.js'); ?>"></script>
<script>
$(function () {
    const urls = {
        data: <?= json_encode(base_url('/ajax/getData')); ?>,
        create: <?= json_encode(base_url('/ajax/create')); ?>,
        update: <?= json_encode(base_url('/ajax/update')); ?>,
        delete: <?= json_encode(base_url('/ajax/delete')); ?>
    };
    let artikelCache = {};

    function escapeHtml(value) {
        return $('<div>').text(value ?? '').html();
    }

    function showMessage(message, type = 'success') {
        $('#ajaxMessage')
            .removeClass('success error')
            .addClass(type)
            .text(message)
            .prop('hidden', false);
    }

    function showErrors(xhr) {
        const response = xhr.responseJSON || {};
        const errors = response.errors ? Object.values(response.errors).join(' ') : response.message;
        showMessage(errors || 'Request AJAX gagal diproses.', 'error');
    }

    function renderRows(rows) {
        artikelCache = {};

        if (! rows.length) {
            $('#artikelTable tbody').html('<tr><td colspan="5" class="empty-cell">Belum ada data artikel.</td></tr>');
            return;
        }

        const html = rows.map(function (row) {
            artikelCache[row.id] = row;
            const statusClass = Number(row.status) === 1 ? 'published' : 'draft';
            const statusText = Number(row.status) === 1 ? 'Terbit' : 'Draft';

            return `<tr>
                <td>${escapeHtml(row.id)}</td>
                <td><strong>${escapeHtml(row.judul)}</strong><small>${escapeHtml(row.isi).slice(0, 70)}</small></td>
                <td><span class="badge">${escapeHtml(row.nama_kategori || 'Tanpa kategori')}</span></td>
                <td><span class="status ${statusClass}">${statusText}</span></td>
                <td class="actions">
                    <button type="button" class="btn btn-small btn-info btn-edit" data-id="${row.id}">Ubah</button>
                    <button type="button" class="btn btn-small btn-danger btn-delete" data-id="${row.id}">Hapus</button>
                </td>
            </tr>`;
        }).join('');

        $('#artikelTable tbody').html(html);
    }

    function loadData() {
        $('#artikelTable tbody').html('<tr><td colspan="5" class="empty-cell">Memuat data...</td></tr>');

        $.getJSON(urls.data)
            .done(function (response) {
                renderRows(response.data || []);
            })
            .fail(showErrors);
    }

    function resetForm() {
        $('#ajaxArtikelForm')[0].reset();
        $('#artikelId').val('');
        $('#formTitle').text('Tambah Artikel');
        $('#ajaxFormPanel').prop('hidden', true);
    }

    $('#btnTambah').on('click', function () {
        resetForm();
        $('#ajaxFormPanel').prop('hidden', false);
        $('#ajaxJudul').trigger('focus');
    });

    $('#btnBatal').on('click', resetForm);

    $('#ajaxArtikelForm').on('submit', function (event) {
        event.preventDefault();

        const id = $('#artikelId').val();
        const requestUrl = id ? `${urls.update}/${id}` : urls.create;

        $.ajax({
            url: requestUrl,
            method: id ? 'PUT' : 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function (response) {
            showMessage(response.message);
            resetForm();
            loadData();
        }).fail(showErrors);
    });

    $(document).on('click', '.btn-edit', function () {
        const row = artikelCache[$(this).data('id')];
        if (! row) return;

        $('#artikelId').val(row.id);
        $('#ajaxJudul').val(row.judul);
        $('#ajaxIsi').val(row.isi);
        $('#ajaxKategori').val(row.id_kategori);
        $('#ajaxStatus').val(row.status);
        $('#formTitle').text('Ubah Artikel');
        $('#ajaxFormPanel').prop('hidden', false);
        $('#ajaxJudul').trigger('focus');
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        if (! confirm('Apakah Anda yakin ingin menghapus artikel ini?')) return;

        $.ajax({
            url: `${urls.delete}/${id}`,
            method: 'DELETE',
            dataType: 'json'
        }).done(function (response) {
            showMessage(response.message);
            loadData();
        }).fail(showErrors);
    });

    loadData();
});
</script>

<?= $this->include('template/footer'); ?>
