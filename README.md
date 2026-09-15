# SIMASS PuTI

Web Aplikasi Presensi, Ticketing, dan Inventory Unit PuTI Telkom University Surabaya.

---

## 🚀 Fitur Utama

- 📌 **Presensi**: Manajemen presensi & kehadiran staf/unit PuTI.
- 🎟️ **Ticketing**: Sistem tiket layanan & dukungan IT unit PuTI.
- 📦 **Inventory**: Manajemen dan pencatatan inventaris barang/aset unit PuTI.

---

## 🛠️ Teknologi yang Digunakan

- **Backend Framework**: Laravel 13 (PHP 8.4)
- **Database**: MySQL 8.0
- **Web Server & Container**: Docker, Docker Compose, Nginx
- **Package Manager**: Composer, NPM
- **Testing & Quality**: Pest PHP, Laravel Pint

---

## 🐳 Cara Menjalankan Aplikasi dengan Docker

1. **Clone Repositori**:
   ```bash
   git clone <URL_REPOSITORI>
   cd simass
   ```

2. **Salin File Environment**:
   ```bash
   cp .env.example .env
   ```

3. **Jalankan Container Docker**:
   ```bash
   docker compose up -d --build
   ```

4. **Generate Application Key & Migrasi Database**:
   ```bash
   docker compose exec app php artisan key:generate
   docker compose exec app php artisan migrate --seed
   ```

5. **Akses Aplikasi**:
   Buka browser dan akses [http://localhost:8000](http://localhost:8000).

---

## 💻 Cara Menjalankan Secara Lokal (Tanpa Docker)

1. **Install Dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

2. **Setup File Environment & Key**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Migrasi Database**:
   ```bash
   php artisan migrate --seed
   ```

4. **Jalankan Server Pengembangan**:
   ```bash
   composer run dev
   # Atau secara terpisah:
   # php artisan serve
   # npm run dev
   ```

---

## 🌿 Aturan Penamaan Branch

Ikuti konvensi penamaan branch berikut saat mengembangkan fitur atau perbaikan:

- `main` : Branch produksi utama (*protected*).
- `develop` : Branch pengembangan utama.
- `feature/<nama-fitur>` : Branch untuk pengembangan fitur baru.
  - *Contoh*: `feature/presensi-qr`, `feature/inventory-asset`
- `bugfix/<nama-bug>` : Branch untuk perbaikan bug biasa.
  - *Contoh*: `bugfix/fix-login-error`, `bugfix/validasi-tiket`
- `hotfix/<nama-hotfix>` : Branch untuk perbaikan cepat/mendesak di lingkungan produksi.
  - *Contoh*: `hotfix/security-patch`

---

## 📝 Aturan Commit (Conventional Commits)

Format pesan commit harus mengikuti standar **Conventional Commits**:

```text
<type>(<scope>): <deskripsi singkat>
```

### Jenis Type yang Digunakan:

| Type | Deskripsi | Contoh Commit |
| :--- | :--- | :--- |
| `feat` | Penambahan fitur baru | `feat(presensi): tambah fitur scan QR code` |
| `fix` | Perbaikan bug | `fix(auth): perbaiki validasi token login` |
| `docs` | Perubahan atau penambahan dokumentasi | `docs(readme): perbarui petunjuk instalasi docker` |
| `style` | Perapihan format kode, spasi, titik koma (tanpa mengubah logika) | `style(pint): format ulang sintaks PHP sesuai pint` |
| `refactor` | Refaktorisasi kode tanpa menambah fitur / memperbaiki bug | `refactor(inventory): optimasi query daftar barang` |
| `test` | Penambahan atau perbaikan unit / feature test | `test(ticket): tambah feature test pembuatan tiket` |
| `chore` | Tugas rutin, pembaruan konfigurasi build, docker, atau dependensi | `chore(docker): perbarui konfigurasi docker-compose` |

### Catatan Penting Commit:
- Gunakan kalimat dalam **huruf kecil** (*lowercase*) untuk deskripsi commit.
- Gunakan bahasa yang jelas, singkat, dan deskriptif (maksimal 50-72 karakter untuk judul commit).
- Hindari pesan commit yang tidak informatif seperti `fix error`, `update`, atau `test`.
