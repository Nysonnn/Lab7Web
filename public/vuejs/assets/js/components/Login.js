const Login = {
    template: `
        <section class="login-container">
            <div class="login-box">
                <span class="section-label">Secure Access</span>
                <h2>Form Login Admin</h2>
                <p class="form-intro">Masuk menggunakan akun administrator yang tersimpan di database CI4.</p>
                <div v-if="notice" class="message warning">{{ notice }}</div>
                <form @submit.prevent="handleLogin">
                    <div class="form-group">
                        <label for="login-username">Username / Email</label>
                        <input id="login-username" type="text" v-model.trim="username" placeholder="Masukkan username atau email" autocomplete="username" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input id="login-password" type="password" v-model="password" placeholder="Masukkan password" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="btn primary full" :disabled="submitting">{{ submitting ? 'Memvalidasi...' : 'Masuk Aplikasi' }}</button>
                </form>
                <p v-if="errorMessage" class="error-msg">{{ errorMessage }}</p>
            </div>
        </section>
    `,
    data() {
        return {
            username: '', password: '', errorMessage: '',
            notice: localStorage.getItem('authNotice') || '', submitting: false
        };
    },
    methods: {
        async handleLogin() {
            this.submitting = true;
            this.errorMessage = '';
            try {
                const response = await axios.post(apiUrl + '/api/login', { username: this.username, password: this.password });
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userToken', response.data.data.token);
                localStorage.setItem('currentUser', response.data.data.username);
                localStorage.removeItem('authNotice');
                this.$root.isLoggedIn = true;
                this.$root.currentUser = response.data.data.username;
                await this.$router.push('/artikel');
            } catch (error) {
                this.errorMessage = readApiError(error, 'Login gagal. Periksa kembali akun Anda.');
            } finally {
                this.submitting = false;
            }
        }
    }
};
