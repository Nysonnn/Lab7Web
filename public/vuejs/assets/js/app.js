const { createApp } = Vue;
const { createRouter, createWebHashHistory } = VueRouter;
const apiUrl = new URL('../', window.location.href).href.replace(/\/$/, '');

function readApiError(error, fallback) {
    const messages = error?.response?.data?.messages;
    if (typeof messages === 'string') return messages;
    if (messages && typeof messages === 'object') return Object.values(messages).join(' ');
    return fallback;
}

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('userToken');
    if (token && String(config.url || '').startsWith(apiUrl)) config.headers.Authorization = 'Bearer ' + token;
    return config;
}, (error) => Promise.reject(error));

axios.interceptors.response.use((response) => response, (error) => {
    const isLoginRequest = String(error?.config?.url || '').endsWith('/api/login');
    if (error?.response?.status === 401 && !isLoginRequest) {
        localStorage.removeItem('isLoggedIn'); localStorage.removeItem('userToken'); localStorage.removeItem('currentUser');
        localStorage.setItem('authNotice', 'Sesi berakhir atau token tidak sah. Silakan login kembali.');
        window.location.hash = '#/login';
    }
    return Promise.reject(error);
});

const routes = [
    { path: '/', component: Home },
    { path: '/api-docs', component: ApiDocs },
    { path: '/login', component: Login },
    { path: '/artikel', component: Artikel, meta: { requiresAuth: true } },
    { path: '/about', component: About, meta: { requiresAuth: true } },
    { path: '/security', component: Security, meta: { requiresAuth: true } }
];
const router = createRouter({ history: createWebHashHistory(), routes });

router.beforeEach((to) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true' && Boolean(localStorage.getItem('userToken'));
    if (to.matched.some((record) => record.meta.requiresAuth) && !isAuthenticated) {
        const message = 'Akses ditolak! Anda harus login terlebih dahulu.';
        localStorage.setItem('authNotice', message);
        window.alert(message);
        return '/login';
    }
    if (to.path === '/login' && isAuthenticated) return '/artikel';
    return true;
});

const app = createApp({
    data() { return { isLoggedIn: localStorage.getItem('isLoggedIn') === 'true', currentUser: localStorage.getItem('currentUser') || '', showLogoutConfirm: false }; },
    methods: {
        logout() { this.showLogoutConfirm = true; },
        async confirmLogout() {
            localStorage.removeItem('isLoggedIn'); localStorage.removeItem('userToken'); localStorage.removeItem('currentUser');
            this.isLoggedIn = false; this.currentUser = ''; this.showLogoutConfirm = false;
            await this.$router.push('/');
        }
    }
});
app.use(router);
app.mount('#app');
