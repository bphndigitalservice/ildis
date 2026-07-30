# ILDIS (Indonesian Law Documentation Information System)

🇮🇩 ILDIS adalah sistem informasi dokumentasi hukum Indonesia yang dikembangkan untuk membantu anggota JDIHN (Jaringan Dokumentasi dan Informasi Hukum Nasional) mengelola data dokumen hukum secara mandiri, efisien, dan sesuai standar.

## Pasang Cepat

Pasang ILDIS dengan satu perintah (memerlukan [Docker](https://docs.docker.com/engine/install/) atau [Podman](https://podman.io/getting-started/installation)):

```bash
curl -fsSL https://raw.githubusercontent.com/bphndigitalservice/ildis/main/install.sh | bash
```

Untuk pemasangan non-interaktif (CI/otomatisasi):

```bash
curl -fsSL https://raw.githubusercontent.com/bphndigitalservice/ildis/main/install.sh | bash -s -- --non-interactive
```

Untuk memperbarui instalasi yang ada:


```bash
./install.sh --update
```

Skrip secara otomatis mendeteksi Docker Compose atau Podman Compose.

## 🔍 Apa itu ILDIS?

ILDIS adalah aplikasi terbuka yang memungkinkan instansi pemerintah pusat maupun daerah untuk:

- Mengelola metadata dokumen hukum (judul, jenis, nomor, tahun, dll)
- Mengunggah file dokumen hukum (PDF, dsb)
- Menyediakan API publik dan terstandar untuk integrasi ke portal JDIHN
- Menyediakan antarmuka pengguna yang sederhana
- Mengelola peran dan pengguna untuk tim pengelola dokumentasi hukum


> ⚠️ Kami sedang dalam proses menyesuaikan ILDIS agar bisa menggunakan dependensi terbaru tanpa breaking compatibility. Kontribusi untuk refactor dan modernisasi sangat dibutuhkan.

## Persyaratan Teknis
- PHP versi 7 atau terbaru
- MySQL Database atau yang mendukung (MariaDB)
- Apache2 sebagai web server

## Pengembangan

### Menggunakan Docker & VSCode
1. Buka repositori ini dengan VSCode kemudian pilih menu `Open in container...`
2. Jalankan program `init` atau `init.bat` (jika menggunakan _Windows_). Pilih opsi yang sesuai hingga selesai.
3. Pasang _dependency_ menggunakan `composer` dengan menjalankan perintah
   ```console
   composer update --ignore-platform-reqs
   ```
4. Salin contoh pengaturan `.env.example`, kemudian isikan pengaturan seperti berikut:
   ```
   # Environment configuration file for the application.
   YII_ENV=prod
   YII_DEBUG=false

   #  Database configuration
   DB_HOST=db # Pengaturan sesuai docker compose
   DB_USER=mariadb # Pengaturan sesuai docker compose
   DB_PASSWORD=mariadb # Pengaturan sesuai docker compose
   DB_DATABASE=mariadb # Pengaturan sesuai docker compose
   DB_DATABASE_PORT=3306
   PUBLIC_DOMAIN=http://ildis-frontend.test

   #  Cookie validation keys for different environments
   COOKIE_VALIDATION_KEY_BE=<Isikan kunci rahasia yang susah ditebak>
   COOKIE_VALIDATION_KEY_FE=<Isikan kunci rahasia yang susah ditebak>

   # reCAPTCHA configuration
   RECAPTCHA_SITE_KEY=
   RECAPTCHA_SECRET_KEY=
   ```
5. Isi database dengan `sql` yang disediakan, ketika ditanya password, isikan dengan `mariadb` sesuai dengan konfigurasi docker-compose
   ```
   mysql -h db -u mariadb -p mariadb < DATABASE/ildis_v4.sql
   ```
6. Jalankan _Debugger_ menu dan pilih `Launch Built-in web server` dan lanjutkan pengembangan.

### Menggunakan Docker (Production)

1. Salin `.env.example` ke `.env` dan sesuaikan konfigurasi.
2. Jalankan `docker compose up -d` — container akan otomatis menjalankan database migration saat pertama kali startup.
3. Aplikasi tersedia di `http://localhost:8080`.

Untuk update ke versi terbaru:
```bash
./update.sh              # Update ke versi terbaru
./update.sh --check      # Cek versi yang tersedia
./update.sh --help       # Lihat semua opsi
```

## 📝 TODO

- [x] Membuat instalasi di _production_ lebih mudah (misalnya dengan Docker atau installer GUI sederhana)
- [x] Update library dengan **CVE** agar sistem lebih aman dan terjaga dari kerentanan
- [x] Panduan pengembangan lokal
- [x] Update ke Versi Yii 2.0.52
- [x] Update ke PHP 8.3
- [x] Migration Script untuk database yang sudah ada
- [ ] Headless mode untuk flexibilitas frontend
- [ ] Dokumentasi API yang lebih lengkap

---

> ILDIS dikembangkan oleh **Pusat Data dan Teknologi Informasi** & **Badan Pembinaan Hukum Nasional** Kementerian Hukum Republik Indonesia sebagai bentuk dukungan terhadap keterbukaan informasi hukum dan penguatan kelembagaan JDIHN.


## Contributing

If you've ever wanted to contribute to open source, and a great cause, now is your chance!

See the [contributing docs](CONTRIBUTING.md) for more information
