# Lab7Web

# Praktikum 1 - PHP Framework CodeIgniter 4

## Nama  : Nysonnn
## Kelas : -
## Matkul: Pemrograman Web 2

---

# 📌 Tujuan Praktikum

1. Memahami konsep dasar Framework.
2. Memahami konsep MVC (Model View Controller).
3. Membuat aplikasi sederhana menggunakan Framework CodeIgniter 4.

---

# ⚙️ Persiapan

Sebelum memulai praktikum, dilakukan persiapan software dan konfigurasi environment.

## Software yang digunakan:
- XAMPP
- Visual Studio Code
- Git
- CodeIgniter 4

---

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

Code:

```php
<?php

namespace App\Controllers;

class Page extends BaseController
{
    public function about()
    {
        return view('about', [
            'title' => 'Halaman About',
            'content' => 'Ini adalah halaman about.'
        ]);
    }

    public function contact()
    {
        return view('contact', [
            'title' => 'Halaman Contact',
            'content' => 'Ini adalah halaman contact.'
        ]);
    }

    public function faqs()
    {
        return view('faqs', [
            'title' => 'Halaman FAQ',
            'content' => 'Ini adalah halaman FAQ.'
        ]);
    }
}
```

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

# ✅ Kesimpulan

Pada praktikum ini berhasil dibuat aplikasi sederhana menggunakan Framework CodeIgniter 4 dengan menerapkan konsep MVC (Model View Controller), routing, controller, view, template layout, dan CSS.

---

# 🔗 Repository GitHub

Tambahkan link repository GitHub di sini.
