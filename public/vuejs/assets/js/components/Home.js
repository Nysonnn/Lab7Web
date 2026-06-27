const Home = {
    template: `
        <section class="home-container">
            <div class="hero-copy">
                <span class="section-label">Single Page Application</span>
                <h2>Selamat datang di Portal Admin Artikel</h2>
                <p>Frontend VueJS ini terhubung ke REST API CodeIgniter 4 dan berpindah halaman tanpa hard reload.</p>
                <router-link class="primary-action" to="/artikel">Buka Manajemen Artikel</router-link>
            </div>
            <div class="feature-grid">
                <article><strong>REST API</strong><span>GET, POST, PUT, DELETE</span></article>
                <article><strong>Vue Router</strong><span>Komponen dan navigasi SPA</span></article>
                <article><strong>Token Auth</strong><span>Bearer token dan interceptor</span></article>
            </div>
        </section>
    `
};
