# INSTRUKSI PENULISAN BAB 5: HASIL DAN PEMBAHASAN

Kamu bertugas menulis **Bab 5 Skripsi** dengan struktur yang sangat spesifik sesuai panduan kampus user.

## 1. STRUKTUR & KONTEN (WAJIB IKUTI URUTAN INI)

Bab ini BUKAN Bab Penutup/Kesimpulan, melainkan berisi Screenshot Aplikasi dan 3 Jenis Pengujian.

### 5.1 Tampilan Aplikasi
Bagian ini menampilkan hasil implementasi antarmuka program.
**Format Penulisan:**
1.  **Paragraf Pengantar:** Jelaskan singkat bahwa ini adalah hasil tampilan yang dibuat berdasarkan akses level (misal: Admin/Direktur/Marketing).
2.  **Item Tampilan (Berulang untuk setiap halaman):**
    * **Judul Gambar:** Gambar 5.[X] Tampilan [Nama Halaman]
    * **[PLACEHOLDER GAMBAR]**
    * **Deskripsi:** Satu paragraf di bawah gambar yang menjelaskan fungsi halaman tersebut. Gunakan gaya bahasa: *"Gambar 5.[X] diatas merupakan tampilan halaman [Nama] yang digunakan aktor untuk [Fungsi Utama]..."*

### 5.2 Uji Coba Aplikasi
Paragraf pembuka: *"Aplikasi yang telah dibuat, selanjutnya diuji melalui teknik pengujian perangkat lunak yang meliputi pengujian struktural, Fungsional dan Validasi."*

#### 5.2.1 Uji Coba Struktural
**Tujuan:** Memastikan layout dan struktur website sesuai rancangan.
**Cara Menulis:**
* Jelaskan bahwa pengujian ini memeriksa kesesuaian *wireframe/design* dengan hasil jadi.
* Buat narasi bahwa navigasi, tombol, dan tata letak menu sudah tertata dengan baik dan tidak ada *broken layout*.
* *Contoh Kalimat:* "Berdasarkan pengujian struktural, tata letak menu *sidebar* dan *header* telah sesuai dengan rancangan. Navigasi antar halaman berjalan lancar tanpa terjadi kesalahan tautan (*broken link*)."

#### 5.2.2 Uji Coba Fungsional
**Tujuan:** Memastikan komponen (tombol/fitur) bekerja.
**Format:** Gunakan **TABEL BLACK BOX TESTING**.
* Buat tabel dengan kolom: **No | Skenario | Hasil Diharapkan | Hasil Pengujian | Kesimpulan**.
* Isi dengan tes CRUD (Create, Read, Update, Delete) dan Login.
* *Kesimpulan wajib:* **Valid**.

#### 5.2.3 Uji Coba Validasi
**Tujuan:** Memastikan logika data benar (Input = Output).
**Cara Menulis:**
* Fokus pada akurasi data.
* Berikan contoh kasus: *"Pada pengujian validasi input data prospek, ketika user memasukkan data 'PT A', sistem berhasil menyimpannya ke database dan menampilkannya kembali pada tabel dengan data yang sama persis."*
* Tegaskan bahwa sistem menolak data yang tidak sesuai format (jika ada validasi error).

---

## 2. GAYA BAHASA (STYLE GUIDE)

* **Formal & Teknis:** Gunakan kata "Sistem", "Pengguna", "Valid", "Sesuai".
* **Deskripsi Gambar:** Jangan hanya tulis "Ini gambar login". Tapi tulis: *"Tampilan Login pada Gambar 5.1 digunakan sebagai gerbang keamanan dimana pengguna wajib memasukkan email dan password."*
* **Konsistensi:** Pastikan nomor gambar berurut (5.1, 5.2, dst).

---

## TUGAS KAMU:

Buatkan **Kerangka Teks Bab 5** untuk sistem **CRM** dengan fitur:
1.  **Tampilan:** Halaman Login, Dashboard, Kelola Prospek, Kelola Aktivitas.
2.  **Pengujian:**
    * **Struktural:** Cek menu responsif.
    * **Fungsional:** Tabel tes Login & Tambah Prospek.
    * **Validasi:** Cek data prospek yang masuk database.
