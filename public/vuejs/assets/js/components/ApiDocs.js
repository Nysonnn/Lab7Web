const ApiDocs = {
    template: `
        <section>
            <div class="page-heading">
                <div><span class="section-label">Praktikum 10</span><h2>REST API CodeIgniter 4</h2><p>Resource controller artikel dengan response JSON dan status HTTP yang sesuai.</p></div>
                <span class="api-health" :class="status === 200 ? 'online' : ''">API {{ status === 200 ? 'Online' : 'Memuat' }}</span>
            </div>
            <div class="endpoint-list">
                <div><code>GET</code><span>/post</span><small>Semua artikel</small></div>
                <div><code>GET</code><span>/post/{id}</span><small>Detail artikel</small></div>
                <div><code>POST</code><span>/post</span><small>Tambah artikel</small></div>
                <div><code>PUT</code><span>/post/{id}</span><small>Ubah artikel</small></div>
                <div><code>DELETE</code><span>/post/{id}</span><small>Hapus artikel</small></div>
            </div>
            <h3 class="response-title">Contoh Response GET /post</h3>
            <pre class="json-response">{{ responseBody }}</pre>
        </section>
    `,
    data() { return { status: null, responseBody: 'Memuat response JSON...' }; },
    async mounted() {
        try {
            const response = await axios.get(apiUrl + '/post');
            this.status = response.status;
            this.responseBody = JSON.stringify({ status: response.data.status, artikel: (response.data.artikel || []).slice(0, 2) }, null, 2);
        } catch (error) {
            this.responseBody = readApiError(error, 'API gagal diakses.');
        }
    }
};
