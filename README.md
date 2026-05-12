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

# 🔗 Repository GitHub

Tambahkan link repository GitHub di sini.

Contoh:

```bash
https://github.com/Nysonnn/Lab7Web
```

---

# 👨‍💻 Author

Nysonnn
