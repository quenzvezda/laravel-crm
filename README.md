### Requirements

-   **SERVER**: Apache 2 or NGINX.
-   **RAM**: 3 GB or higher.
-   **PHP**: 8.1 or higher
-   **For MySQL users**: 5.7.23 or higher.
-   **For MariaDB users**: 10.2.7 or Higher.
-   **Node**: 8.11.3 LTS or higher.
-   **Composer**: 2.5 or higher

### Installation and Configuration

##### Execute these commands below, in order

```
composer create-project
```

-   Find **.env** file in root directory and change the **APP_URL** param to your **domain**.

-   Also, Configure the **Mail** and **Database** parameters inside **.env** file.

```
php artisan krayin-crm:install
```

**To execute Krayin**:

##### On server:

Warning: Before going into production mode we recommend you uninstall developer dependencies.
In order to do that, run the command below:

> composer install --no-dev

```
Open the specified entry point in your hosts file in your browser or make an entry in hosts file if not done.
```

##### On local:

```
php artisan route:clear
php artisan serve
```


**How to log in as admin:**

> _http(s)://example.com/admin/login_

```
email:admin@example.com
password:admin123
```

### Seeding Data dengan Custom Attributes (EAV) – Penting

Krayin menggunakan pola EAV (Entity–Attribute–Value) untuk banyak field di UI. Pada form, nilai atribut dibaca dari tabel `attribute_values` dan dapat menimpa nilai kolom inti (mis. `persons.organization_id`, `organizations.address`, dll). Akibatnya, data hasil seeding yang hanya mengisi kolom tabel bisa terlihat di listing, tetapi kosong/tidak muncul di komponen lookup sampai `attribute_values` ikut diisi.

- Gejala umum jika `attribute_values` tidak diisi:
  - Field lookup (mis. Organization pada Person, Person pada Quote) tampak kosong di halaman edit, baru muncul setelah Anda klik Save sekali.
  - Error filter di lookup: `item.name is null` saat pencarian.

- Guardrails saat membuat seeder untuk entitas yang punya custom attributes:
  - Isi tabel utama (mis. `persons`, `organizations`) seperti biasa menggunakan `insert/upsert`.
  - Lalu isi juga tabel EAV `attribute_values` untuk atribut yang dipakai di form/lookup.
  - Set kolom `user_id` (owner) pada record utama DAN simpan juga sebagai attribute (`user_id` → tipe lookup/integer) agar lolos batasan ACL (authorized user ids) di pencarian/lookup.
  - Komponen lookup mulai mencari setelah ≥ 3 karakter; ini normal.

- Checklist atribut yang umum:
  - Persons (`entity_type = persons`): `name` (text), `emails` (json), `contact_numbers` (json), `job_title` (text), `user_id` (integer/lookup), `organization_id` (integer/lookup).
  - Organizations (`entity_type = organizations`): `name` (text), `address` (json), `user_id` (integer/lookup).

- Contoh pola seeding ringkas (pseudo):

```
// 1) Upsert tabel utama
DB::table('persons')->upsert($rows, ['unique_id'], ['name', 'emails', '...']);

// 2) Ambil id atribut yang relevan
$attrIds = DB::table('attributes')
  ->where('entity_type', 'persons')
  ->whereIn('code', ['name','emails','contact_numbers','job_title','user_id','organization_id'])
  ->pluck('id','code');

// 3) Susun baris EAV sesuai tipe field (text/json/integer) dan upsert
DB::table('attribute_values')->upsert($attributeValues,
  ['entity_type','entity_id','attribute_id'],
  ['text_value','boolean_value','integer_value','float_value','datetime_value','date_value','json_value']);

// Catatan: set juga owner `user_id` (mis. admin@example.com) pada record utama dan EAV.
```

- Jika sudah terlanjur seeding tanpa EAV: jalankan `php artisan migrate:fresh --seed` (atau seed ulang seeder terkait) lalu `php artisan optimize:clear`.

**Analytical CRM – Demo Seeder (Probabilitas)**

- Tujuan: menghasilkan dataset demo Leads/Quotes yang lebih natural (komposisi item bervariasi, volume harian niche, dan hasil Apriori yang tidak seragam). Seeder membaca parameter probabilitas dari file `.env` dengan default berikut:

- Parameter `.env` (dengan default):
  - `ANALYTIC_DEMO_P_ANCHOR=0.85`
    - Probabilitas setiap item “anchor” (2 komponen inti pertama per bundle) muncul di suatu quote. Menaikkan nilai ini cenderung menaikkan confidence antar komponen inti.
  - `ANALYTIC_DEMO_P_OPTIONAL=0.55`
    - Probabilitas item “core base” lainnya (di luar 2 anchor) muncul di suatu quote.
  - `ANALYTIC_DEMO_P_LONGTAIL=0.18`
    - Peluang menambahkan 1 item long‑tail ke suatu quote (maksimal satu per quote oleh seeder ini).
  - `ANALYTIC_DEMO_P_CROSS=0.08`
    - Peluang menyisipkan 1 item mid dari bundle lain (cross‑bundle) untuk menciptakan korelasi lemah antar bundle.
  - `ANALYTIC_DEMO_MID_LAMBDA=1.5`
    - Parameter λ untuk memilih jumlah item kategori “mid” per quote menggunakan distribusi Poisson terpotong pada rentang 0..`ANALYTIC_DEMO_MID_MAXK`. Semakin besar λ, rata‑rata item mid/quote meningkat.
  - `ANALYTIC_DEMO_MID_MAXK=3`
    - Batas atas jumlah item “mid” yang bisa dipilih per quote.
  - `ANALYTIC_DEMO_BUNDLE_PRIMARY_WEIGHT=0.80`
    - Peluang organisasi tetap memakai bundle utamanya saat membentuk basket (20% sisanya beralih ke bundle lain secara acak untuk variasi kecil).

- Perilaku lain seeder (ringkas):
  - Periode tanggal: 2025‑01‑01 s/d 2025‑11‑30.
  - Volume harian niche: weekday umumnya 0–2 leads/hari, weekend lebih sering 0.
  - 1 lead = 1 quote (tanpa revisi); total target leads tepat 300 (kuota harian disesuaikan otomatis agar mencapai total).

- Cara mengubah perilaku:
  - Edit nilai di `.env` sesuai kebutuhan.
  - Muat ulang konfigurasi: `php artisan optimize:clear`.
  - Regenerasi data demo: `php artisan migrate:fresh --seed`.
  - Jalankan analisis Apriori (contoh):
    - `php artisan analytics:apriori --from=2025-01-01 --to=2025-11-30 --support=0.05 --confidence=0.6 --min-items=2 --save --activate`
