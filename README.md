# Lab7Web

# Praktikum 1 & 2 - PHP Framework CodeIgniter 4

## Nama  : Nysonnn
## Kelas : -
## Matkul: Pemrograman Web 2

---

# 📌 Tujuan Praktikum

## Praktikum 1
1. Memahami konsep dasar Framework.
2. Memahami konsep MVC (Model View Controller).
3. Membuat aplikasi sederhana menggunakan Framework CodeIgniter 4.

## Praktikum 2
1. Memahami konsep dasar Model.
2. Memahami konsep dasar CRUD.
3. Membuat aplikasi CRUD sederhana menggunakan CodeIgniter 4.

---

# ⚙️ Persiapan

Sebelum memulai praktikum, dilakukan persiapan software dan konfigurasi environment.

## Software yang digunakan:
- XAMPP
- Visual Studio Code
- Git
- CodeIgniter 4
- MySQL

---

# 🚀 Praktikum 1 - Framework CodeIgniter 4

# 1️⃣ Membuat Folder Project

Membuat folder project pada direktori htdocs.

Lokasi folder:

```bash
C:\xampp\htdocs\lab11_ci
```

## Screenshot
![Screenshot Folder Project](screenshots/folder-project.png)

---

# 2️⃣ Install CodeIgniter 4

Download CodeIgniter 4 dari website resmi kemudian extract ke folder project.

Folder hasil extract:

```bash
C:\xampp\htdocs\lab11_ci\ci4
```

Menjalankan project menggunakan terminal:

```bash
php spark serve
```

## Screenshot
![Screenshot Install CI4](screenshots/install-ci4.png)

---

# 3️⃣ Mengaktifkan Extension PHP

Mengaktifkan extension pada file `php.ini`:

- intl
- mysqli
- curl
- json
- xml

Kemudian restart Apache.

## Screenshot
![Screenshot PHP.ini](screenshots/phpini.png)

---

# 4️⃣ Menjalankan Server CodeIgniter

Menjalankan server bawaan CodeIgniter menggunakan command:

```bash
php spark serve
```

Server berjalan pada:

```bash
http://localhost:8080
```

## Screenshot
![Screenshot Spark Serve](screenshots/spark-serve.png)

---

# 5️⃣ Membuat Routing

Routing digunakan untuk mengatur halaman yang dapat diakses.

File:

```bash
app/Config/Routes.php
```

Code:

```php
$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
```

## Screenshot
![Screenshot Routes](screenshots/routes.png)

---

# 6️⃣ Membuat Controller

Membuat controller `Page.php`.

Lokasi file:

```bash
app/Controllers/Page.php
```

Controller digunakan untuk menghubungkan routing dengan tampilan halaman.

## Screenshot
![Screenshot Controller](screenshots/controller.png)

---

# 7️⃣ Membuat View

Membuat file view:
- about.php
- contact.php
- faqs.php

Lokasi:

```bash
app/Views/
```

View digunakan untuk menampilkan halaman website.

## Screenshot
![Screenshot View](screenshots/view.png)

---

# 8️⃣ Membuat Layout Template

Membuat:
- header.php
- footer.php

Lokasi:

```bash
app/Views/template/
```

Template digunakan agar tampilan website lebih rapih dan konsisten.

## Screenshot
![Screenshot Template](screenshots/template.png)

---

# 9️⃣ Membuat CSS

Membuat file CSS pada:

```bash
public/style.css
```

CSS digunakan untuk mempercantik tampilan website.

## Screenshot
![Screenshot CSS](screenshots/css.png)

---

# 🔟 Hasil Tampilan Website

## Halaman About
![Screenshot About](screenshots/about.png)

## Halaman Contact
![Screenshot Contact](screenshots/contact.png)

## Halaman FAQ
![Screenshot FAQ](screenshots/faqs.png)

---

# 1️⃣1️⃣ Menampilkan Routes

Menjalankan perintah:

```bash
php spark routes
```

Untuk melihat daftar routing yang aktif.

## Screenshot
![Screenshot Routes CMD](screenshots/spark-routes.png)

---

# 🚀 Praktikum 2 - CRUD Artikel

# 1️⃣ Membuat Database

Membuat database baru menggunakan MySQL.

SQL:

```sql
CREATE DATABASE lab_ci4;
```

## Screenshot
![Screenshot Database](screenshots/database.png)

---

# 2️⃣ Membuat Tabel Artikel

Membuat tabel artikel untuk menyimpan data artikel.

SQL:

```sql
CREATE TABLE artikel (
    id INT(11) auto_increment,
    judul VARCHAR(200) NOT NULL,
    isi TEXT,
    gambar VARCHAR(200),
    status TINYINT(1) DEFAULT 0,
    slug VARCHAR(200),
    PRIMARY KEY(id)
);
```

## Screenshot
![Screenshot Table Artikel](screenshots/table-artikel.png)

---

# 3️⃣ Menambahkan Data Artikel

Menambahkan data artikel awal ke database.

SQL:

```sql
INSERT INTO artikel (judul, isi, slug) VALUES
(
'Artikel Pertama',
'Ini isi artikel pertama praktikum CodeIgniter 4',
'artikel-pertama'
),
(
'Artikel Kedua',
'Ini isi artikel kedua praktikum CodeIgniter 4',
'artikel-kedua'
);
```

## Screenshot
![Screenshot Insert Artikel](screenshots/insert-artikel.png)

---

# 4️⃣ Konfigurasi Database

Melakukan konfigurasi database pada file `.env`.

Code:

```ini
database.default.hostname = localhost
database.default.database = lab_ci4
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

## Screenshot
![Screenshot Database Config](screenshots/db-config.png)

---

# 5️⃣ Membuat Model

Membuat model `ArtikelModel.php`.

Lokasi:

```bash
app/Models/ArtikelModel.php
```

Model digunakan untuk menghubungkan aplikasi dengan database.

## Screenshot
![Screenshot Model](screenshots/model.png)

---

# 6️⃣ Membuat Controller Artikel

Membuat controller `Artikel.php`.

Lokasi:

```bash
app/Controllers/Artikel.php
```

Controller digunakan untuk mengambil data artikel dari database.

## Screenshot
![Screenshot Artikel Controller](screenshots/artikel-controller.png)

---

# 7️⃣ Membuat View Artikel

Membuat halaman tampilan artikel.

Lokasi:

```bash
app/Views/artikel/index.php
```

View digunakan untuk menampilkan data artikel dari database.

## Screenshot
![Screenshot View Artikel](screenshots/view-artikel.png)

---

# 8️⃣ Menambahkan Routing Artikel

Menambahkan route untuk halaman artikel.

Code:

```php
$routes->get('/artikel', 'Artikel::index');
```

## Screenshot
![Screenshot Routes Artikel](screenshots/routes-artikel.png)

---

# 9️⃣ Menampilkan Halaman Artikel

Menjalankan halaman artikel pada browser:

```bash
http://localhost:8080/artikel
```

Data artikel berhasil tampil dari database MySQL.

## Screenshot
![Screenshot Halaman Artikel](screenshots/halaman-artikel.png)

---

# 🔟 Menjalankan PHP Spark Routes

Menampilkan daftar route aktif menggunakan command:

```bash
php spark routes
```

## Screenshot
![Screenshot Spark Routes](screenshots/spark-routes.png)

---

# ✅ Kesimpulan

Pada praktikum ini berhasil dibuat aplikasi sederhana menggunakan Framework CodeIgniter 4 dengan menerapkan:
- MVC (Model View Controller)
- Routing
- Controller
- View
- Template Layout
- CSS
- CRUD sederhana
- Koneksi Database MySQL

---

---

# 🚀 Praktikum 3 - View Layout dan View Cell

# 1️⃣ Membuat Layout Utama

Membuat folder layout pada direktori:

```bash
app/Views/layout
```

Kemudian membuat file:

```bash
main.php
```

Layout digunakan sebagai template utama agar tampilan website lebih rapih dan dapat digunakan ulang pada banyak halaman.

## Screenshot
![Screenshot Layout Main](screenshots/layout-main.png)

---

# 2️⃣ Mengubah View Menggunakan Layout

Mengubah file view agar menggunakan layout baru dengan method:

```php
<?= $this->extend('layout/main') ?>
```

dan:

```php
<?= $this->section('content') ?>
```

View yang diubah:
- about.php
- contact.php
- faqs.php

## Screenshot
![Screenshot About Layout](screenshots/about-layout.png)

---

# 3️⃣ Membuat View Cell

Membuat folder:

```bash
app/Cells
```

Kemudian membuat file:

```bash
ArtikelTerkini.php
```

View Cell digunakan untuk membuat komponen yang dapat digunakan ulang seperti sidebar dan widget artikel terbaru.

## Screenshot
![Screenshot Artikel Terkini Cell](screenshots/artikel-terkini-cell.png)

---

# 4️⃣ Membuat View Components

Membuat folder:

```bash
app/Views/components
```

Kemudian membuat file:

```bash
artikel_terkini.php
```

View component digunakan untuk menampilkan daftar artikel terbaru pada sidebar.

## Screenshot
![Screenshot Artikel Terkini View](screenshots/artikel-terkini-view.png)

---

# 5️⃣ Menampilkan Layout Website

Menjalankan website menggunakan layout baru yang sudah menggunakan:
- Navbar
- Sidebar
- Widget
- Artikel terbaru

URL:

```bash
http://localhost:8080/about
```

## Screenshot
![Screenshot Halaman Layout](screenshots/halaman-layout.png)

---

# 6️⃣ Menampilkan Sidebar Widget

Sidebar menampilkan:
- Artikel terbaru
- Widget link
- Widget text

## Screenshot
![Screenshot Sidebar Widget](screenshots/sidebar-widget.png)

---

# 7️⃣ Menjalankan PHP Spark Serve

Menjalankan server CodeIgniter menggunakan command:

```bash
php spark serve
```

## Screenshot
![Screenshot Spark Serve P3](screenshots/spark-serve-p3.png)

---

# 8️⃣ Menampilkan Routes

Menampilkan route aktif menggunakan command:

```bash
php spark routes
```

## Screenshot
![Screenshot Spark Routes P3](screenshots/spark-routes-p3.png)

---

# 📌 Jawaban Pertanyaan Praktikum

## 1. Apa manfaat utama dari penggunaan View Layout?

View Layout mempermudah pengelolaan tampilan website karena template utama cukup dibuat satu kali dan dapat digunakan ulang pada banyak halaman sehingga kode menjadi lebih rapih dan efisien.

---

## 2. Jelaskan perbedaan antara View Cell dan View biasa.

- View biasa digunakan untuk menampilkan halaman utama website.
- View Cell digunakan untuk membuat komponen kecil yang reusable seperti sidebar, widget, menu, dan artikel terbaru.

---

## 3. Ubah View Cell agar hanya menampilkan post dengan kategori tertentu.

Contoh query:

```php
$model->where('kategori', 'Teknologi')->findAll();
```

Dengan query tersebut View Cell hanya akan menampilkan artikel berdasarkan kategori tertentu.

---

---

# 🚀 Praktikum 4 - Modul Login dan Auth Filter

# 1️⃣ Membuat Tabel User

Membuat tabel user untuk menyimpan data login administrator.

SQL:

```sql
CREATE TABLE user (
    id INT(11) auto_increment,
    username VARCHAR(200) NOT NULL,
    useremail VARCHAR(200),
    userpassword VARCHAR(200),
    PRIMARY KEY(id)
);
```

## Screenshot
![Screenshot Table User](screenshots/table-user.png)

---

# 2️⃣ Membuat User Model

Membuat file model:

```bash
app/Models/UserModel.php
```

Model digunakan untuk menghubungkan data user dengan database.

## Screenshot
![Screenshot User Model](screenshots/user-model.png)

---

# 3️⃣ Membuat Controller User

Membuat controller:

```bash
app/Controllers/User.php
```

Controller digunakan untuk:
- proses login
- validasi user
- membuat session login
- logout

## Screenshot
![Screenshot User Controller](screenshots/user-controller.png)

---

# 4️⃣ Membuat View Login

Membuat halaman login:

```bash
app/Views/user/login.php
```

Halaman login digunakan untuk autentikasi user sebelum masuk ke halaman admin.

## Screenshot
![Screenshot Login View](screenshots/login-view.png)

---

# 5️⃣ Membuat Database Seeder

Membuat seeder untuk memasukkan akun admin secara otomatis.

Command:

```bash
php spark make:seeder UserSeeder
```

Seeder digunakan untuk membuat data dummy login administrator.

## Screenshot
![Screenshot User Seeder](screenshots/user-seeder.png)

---

# 6️⃣ Menjalankan Seeder

Menjalankan seeder menggunakan command:

```bash
php spark db:seed UserSeeder
```

Data admin berhasil masuk ke database.

## Screenshot
![Screenshot Spark Seeder](screenshots/spark-seeder.png)

---

# 7️⃣ Membuat Auth Filter

Membuat file filter:

```bash
app/Filters/Auth.php
```

Filter digunakan untuk melindungi halaman admin agar hanya dapat diakses setelah login.

## Screenshot
![Screenshot Auth Filter](screenshots/auth-filter.png)

---

# 8️⃣ Menambahkan Config Filter

Menambahkan auth filter pada file:

```bash
app/Config/Filters.php
```

Code:

```php
'auth' => \App\Filters\Auth::class,
```

## Screenshot
![Screenshot Filters Config](screenshots/filters-config.png)

---

# 9️⃣ Menambahkan Routes Login

Menambahkan route login dan logout pada file:

```bash
app/Config/Routes.php
```

Code:

```php
$routes->match(['get', 'post'], 'user/login', 'User::login');

$routes->get('user/logout', 'User::logout');
```

Serta menambahkan proteksi auth filter pada halaman admin.

## Screenshot
![Screenshot Routes Login](screenshots/routes-login.png)

---

# 🔟 Menampilkan Halaman Login

Menjalankan halaman login pada browser:

```bash
http://localhost:8080/user/login
```

## Screenshot
![Screenshot Halaman Login](screenshots/halaman-login.png)

---

# 1️⃣1️⃣ Login Berhasil

Melakukan login menggunakan akun administrator:

Email:

```bash
admin@email.com
```

Password:

```bash
admin123
```

Setelah login berhasil maka user diarahkan ke halaman admin artikel.

## Screenshot
![Screenshot Login Berhasil](screenshots/login-berhasil.png)

---

# 1️⃣2️⃣ Redirect Login

Ketika user belum login dan mencoba mengakses halaman admin:

```bash
http://localhost:8080/admin/artikel
```

maka sistem otomatis redirect ke halaman login.

## Screenshot
![Screenshot Redirect Login](screenshots/redirect-login.png)

---

# ✅ Kesimpulan Praktikum 4

Pada praktikum ini berhasil dibuat:
- Sistem Login
- Session Login
- Logout
- Auth Filter
- Redirect Login
- Proteksi halaman admin menggunakan filter authentication

Dengan demikian halaman admin menjadi lebih aman karena hanya dapat diakses oleh user yang sudah login.

---


# 🔗 Repository GitHub

Tambahkan link repository GitHub di sini.

Contoh:

```bash
https://github.com/Nysonnn/Lab7Web
```

---

# 👨‍💻 Author

Nysonnn
