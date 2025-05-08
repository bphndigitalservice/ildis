# ILDIS (Indonesian Law Documentation Information System)

🇮🇩 ILDIS adalah sistem informasi dokumentasi hukum Indonesia yang dikembangkan untuk membantu anggota JDIHN (Jaringan Dokumentasi dan Informasi Hukum Nasional) mengelola data dokumen hukum secara mandiri, efisien, dan sesuai standar.

## 🔍 Apa itu ILDIS?

ILDIS adalah aplikasi terbuka yang memungkinkan instansi pemerintah pusat maupun daerah untuk:

- Mengelola metadata dokumen hukum (judul, jenis, nomor, tahun, dll)
- Mengunggah file dokumen hukum (PDF, dsb)
- Menyediakan API publik dan terstandar untuk integrasi ke portal JDIHN
- Menyediakan antarmuka pengguna yang sederhana
- Mengelola peran dan pengguna untuk tim pengelola dokumentasi hukum


## 🧱 Vendor Legacy (Sementara)

Untuk saat ini, ILDIS masih menggunakan *vendor dependencies* dari project legacy yang sudah dikompres dalam file `vendor.zip`. Hal ini dilakukan untuk menjaga **kompatibilitas** dan memastikan sistem tetap berjalan sembari kami melakukan refactor dan migrasi bertahap ke versi library terbaru.

### 📥 Cara Pakai

1. Unduh file `vendor.zip` di [sini](https://box.bphn.go.id/index.php/s/Dbw9tX6b2RzA5ij).
2. Ekstrak file tersebut ke dalam folder `vendor/` di root project.
3. Lewati perintah `composer install` (untuk sementara waktu).

> ⚠️ Kami sedang dalam proses menyesuaikan ILDIS agar bisa menggunakan dependensi terbaru tanpa breaking compatibility. Kontribusi untuk refactor dan modernisasi sangat dibutuhkan.



## 📝 TODO

- [ ] Membuat instalasi di _production_ lebih mudah (misalnya dengan Docker atau installer GUI sederhana)
- [ ] Update library dengan **CVE** agar sistem lebih aman dan terjaga dari kerentanan
- [ ] Panduan pengembangan lokal

---

> ILDIS dikembangkan oleh **Pusat Data dan Teknologi Informasi** & **Badan Pembinaan Hukum Nasional** Kementerian Hukum Republik Indonesia sebagai bentuk dukungan terhadap keterbukaan informasi hukum dan penguatan kelembagaan JDIHN.
