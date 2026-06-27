const Security = {
    template: `
        <section>
            <div class="page-heading">
                <div>
                    <span class="section-label">Praktikum 14</span>
                    <h2>API Security Diagnostics</h2>
                    <p>Membandingkan request ilegal tanpa token dan request Axios dengan Bearer token otomatis.</p>
                </div>
                <button class="btn primary" @click="runChecks" :disabled="loading">{{ loading ? 'Menguji...' : 'Uji Ulang' }}</button>
            </div>

            <div class="security-grid">
                <article class="diagnostic-card denied">
                    <div class="diagnostic-heading"><span>Tanpa Token</span><strong>HTTP {{ unauthorized.status || '—' }}</strong></div>
                    <p>POST langsung ke <code>/post</code> tanpa header Authorization.</p>
                    <pre>{{ unauthorized.body || 'Menunggu pengujian...' }}</pre>
                </article>
                <article class="diagnostic-card allowed">
                    <div class="diagnostic-heading"><span>Axios Interceptor</span><strong>HTTP {{ authorized.status || '—' }}</strong></div>
                    <p>Token disuntikkan otomatis sebelum request dikirim.</p>
                    <pre>{{ authorized.body || 'Menunggu pengujian...' }}</pre>
                </article>
            </div>

            <div class="security-note">
                <strong>Lapisan perlindungan:</strong>
                <span>Vue Router Guard melindungi navigasi antarmuka, sedangkan CodeIgniter Filter melindungi endpoint dan database pada sisi server.</span>
            </div>
        </section>
    `,
    data() {
        return {
            loading: false,
            unauthorized: { status: null, body: '' },
            authorized: { status: null, body: '' }
        };
    },
    mounted() { this.runChecks(); },
    methods: {
        async runChecks() {
            this.loading = true;

            try {
                const response = await fetch(apiUrl + '/post', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ judul: 'Percobaan ilegal tanpa token' })
                });
                const body = await response.json();
                this.unauthorized = { status: response.status, body: JSON.stringify(body, null, 2) };
            } catch (error) {
                this.unauthorized = { status: 'ERR', body: error.message };
            }

            try {
                const response = await axios.get(apiUrl + '/api/token-check');
                this.authorized = { status: response.status, body: JSON.stringify(response.data, null, 2) };
            } catch (error) {
                this.authorized = { status: error?.response?.status || 'ERR', body: readApiError(error, error.message) };
            } finally {
                this.loading = false;
            }
        }
    }
};
