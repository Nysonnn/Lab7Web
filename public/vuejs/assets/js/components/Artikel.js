const Artikel = {
    template: `
        <section>
            <div class="page-heading">
                <div><span class="section-label">Protected Resource</span><h2>Manajemen Data Artikel</h2><p>CRUD melalui REST API dengan token yang disuntikkan Axios Interceptor.</p></div>
                <button class="btn primary" @click="tambah">+ Tambah Data</button>
            </div>
            <div v-if="message" class="message success">{{ message }}</div>
            <div v-if="errorMessage" class="message danger">{{ errorMessage }}</div>
            <div v-if="loading" class="loading-state"><span class="spinner"></span> Memuat artikel...</div>

            <div class="modal" v-if="showForm" @click.self="closeForm">
                <div class="modal-content">
                    <button class="close" type="button" aria-label="Tutup form" @click="closeForm">×</button>
                    <span class="section-label">Form Artikel</span><h3>{{ formTitle }}</h3>
                    <form @submit.prevent="saveData">
                        <div class="form-group"><label for="judul">Judul</label><input id="judul" type="text" v-model.trim="formData.judul" placeholder="Judul artikel" required></div>
                        <div class="form-group"><label for="isi">Isi</label><textarea id="isi" rows="6" v-model.trim="formData.isi" placeholder="Isi artikel" required></textarea></div>
                        <div class="form-grid">
                            <div class="form-group"><label for="kategori">Kategori</label><select id="kategori" v-model="formData.id_kategori"><option value="">Tanpa kategori</option><option v-for="option in kategori" :key="option.id_kategori" :value="option.id_kategori">{{ option.nama_kategori }}</option></select></div>
                            <div class="form-group"><label for="status">Status</label><select id="status" v-model="formData.status"><option :value="0">Draft</option><option :value="1">Publish</option></select></div>
                        </div>
                        <div class="form-actions"><button class="btn primary" type="submit" :disabled="saving">{{ saving ? 'Menyimpan...' : 'Simpan' }}</button><button class="btn secondary" type="button" @click="closeForm">Batal</button></div>
                    </form>
                </div>
            </div>

            <div class="table-shell" v-if="!loading">
                <table>
                    <thead><tr><th>ID</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <tr v-for="(row, index) in artikel" :key="row.id">
                            <td>{{ row.id }}</td><td><strong>{{ row.judul }}</strong><small>{{ excerpt(row.isi) }}</small></td>
                            <td><span class="category-chip">{{ row.nama_kategori || 'Tanpa kategori' }}</span></td>
                            <td><span class="status-chip" :class="Number(row.status) === 1 ? 'publish' : 'draft'">{{ statusText(row.status) }}</span></td>
                            <td class="actions"><button class="btn mini info" @click="edit(row)">Edit</button><button class="btn mini danger" @click="hapus(index, row.id)">Hapus</button></td>
                        </tr>
                        <tr v-if="artikel.length === 0"><td colspan="5" class="empty">Belum ada data artikel.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>
    `,
    data() {
        return {
            artikel: [], kategori: [], formData: { id: null, judul: '', isi: '', status: 0, id_kategori: '' },
            showForm: false, formTitle: 'Tambah Data', loading: true, saving: false, message: '', errorMessage: ''
        };
    },
    mounted() { this.loadData(); },
    methods: {
        async loadData() {
            this.loading = true; this.errorMessage = '';
            try {
                const response = await axios.get(apiUrl + '/post');
                this.artikel = response.data.artikel || [];
                this.kategori = response.data.kategori || [];
            } catch (error) { this.errorMessage = readApiError(error, 'Data artikel gagal dimuat.'); }
            finally { this.loading = false; }
        },
        tambah() { this.formTitle = 'Tambah Data'; this.formData = { id: null, judul: '', isi: '', status: 0, id_kategori: '' }; this.showForm = true; },
        edit(data) {
            this.formTitle = 'Ubah Data';
            this.formData = { id: data.id, judul: data.judul, isi: data.isi, status: Number(data.status), id_kategori: data.id_kategori || '' };
            this.showForm = true;
        },
        closeForm() { this.showForm = false; this.errorMessage = ''; },
        async saveData() {
            this.saving = true; this.errorMessage = '';
            try {
                const isUpdate = Boolean(this.formData.id);
                const url = isUpdate ? apiUrl + '/post/' + this.formData.id : apiUrl + '/post';
                const response = isUpdate ? await axios.put(url, this.formData) : await axios.post(url, this.formData);
                this.message = response.data.messages.success;
                this.showForm = false;
                await this.loadData();
            } catch (error) { this.errorMessage = readApiError(error, 'Data artikel gagal disimpan.'); }
            finally { this.saving = false; }
        },
        async hapus(index, id) {
            if (!confirm('Yakin menghapus data artikel ini?')) return;
            this.errorMessage = '';
            try {
                const response = await axios.delete(apiUrl + '/post/' + id);
                this.artikel.splice(index, 1);
                this.message = response.data.messages.success;
            } catch (error) { this.errorMessage = readApiError(error, 'Data artikel gagal dihapus.'); }
        },
        statusText(status) { return Number(status) === 1 ? 'Publish' : 'Draft'; },
        excerpt(value) { const text = String(value || '').replace(/<[^>]*>/g, '').trim(); return text.length > 80 ? text.slice(0, 80) + '…' : text; }
    }
};
