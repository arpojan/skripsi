# 🦎 RAP Enclosure DSS

> **Sistem Informasi Monitoring dan Decision Support System Rule-Based pada Smart Enclosure Hewan Eksotis Menggunakan Internet of Things dan Aplikasi Web**

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![ESP32](https://img.shields.io/badge/IoT-ESP32-E7352C?style=flat&logo=espressif&logoColor=white)](https://espressif.com)
[![SUS Score](https://img.shields.io/badge/SUS_Score-83.50%2F100-brightgreen?style=flat)](#pengujian)

---

## 📖 Tentang Proyek

**RAP Enclosure DSS** adalah sistem informasi berbasis web dan IoT untuk monitoring kondisi lingkungan enclosure hewan eksotis (reptil dan amfibi) secara real-time, dilengkapi dengan **Decision Support System (DSS) berbasis aturan (rule-based)** yang membantu pengguna dalam pengambilan keputusan pengaturan parameter misting.

Sistem ini dikembangkan sebagai tugas akhir (skripsi) Program Studi Sistem Informasi, Universitas Gunadarma.

**Cakupan penelitian:**
- Aplikasi web sebagai platform monitoring, konfigurasi, dan DSS
- ESP32 berperan sebagai sumber data (sensor) — pengembangan hardware IoT berada di luar cakupan penelitian

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|---|---|
| 🔐 **Autentikasi** | Login multi-user dengan pemilihan enclosure |
| 📊 **Dashboard Real-time** | Monitoring suhu, kelembapan, status misting, dan koneksi perangkat |
| 🧠 **DSS Rule-Based** | Rekomendasi parameter misting berbasis aturan (threshold, stability score) |
| ✅ **Human-in-the-Loop** | Mekanisme Apply/Reject untuk setiap rekomendasi DSS |
| 📈 **Analytics** | Grafik historis, distribusi kelembapan, rata-rata RH & suhu, siklus misting |
| 🏆 **Stability Score** | Skor stabilitas enclosure: RC (40%), VS (30%), SDR (20%), FP (maks 20 poin) |
| 🐍 **Knowledge Base** | 28 spesies (ular, kadal, gecko, kura-kura darat, katak/kodok) dengan 8 threshold suhu & kelembapan |
| 📝 **Parameter History** | Riwayat perubahan parameter manual dan dari rekomendasi DSS |
| 📡 **REST API** | Endpoint untuk telemetry ESP32 dan pengambilan konfigurasi |

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                    HARDWARE LAYER                   │
│  DHT22 Sensor → ESP32 → Rule-Based Misting Lokal   │
│                    ↕ REST API                        │
├─────────────────────────────────────────────────────┤
│                   APPLICATION LAYER                 │
│  Laravel 11 + MySQL + Tailwind CSS (Hostinger)      │
│                                                     │
│  ┌─────────┐  ┌───────────┐  ┌──────────────────┐  │
│  │Dashboard│  │ Analytics │  │  DSS Rule-Based  │  │
│  │Realtime │  │Historis   │  │ + Apply / Reject │  │
│  └─────────┘  └───────────┘  └──────────────────┘  │
└─────────────────────────────────────────────────────┘
```

**Flow Data:**
```
ESP32 baca sensor (DHT22)
   ↓
ESP32 ambil konfigurasi terbaru dari web → GET /api/enclosures/{id}/control-config
   ↓
ESP32 eksekusi rule-based misting secara lokal
   ↓
ESP32 kirim telemetry + status misting aktual → POST /api/telemetry
   ↓
Laravel simpan data, hitung Stability Score, jalankan DSS Rule-Based
   ↓
Pengguna lihat dashboard → baca rekomendasi DSS → Apply / Reject
   ↓
Konfigurasi terbaru siap diambil ESP32 pada siklus berikutnya
```

> ⚠️ **Catatan:** Laravel/web **bukan** pengambil keputusan ON/OFF misting utama. Eksekusi misting dilakukan sepenuhnya oleh ESP32 berdasarkan threshold yang dikonfigurasi.

---

## 🛠️ Tech Stack

### Web Application
| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel 11 |
| Bahasa | PHP 8.2+ |
| Database | MySQL 8.0 |
| Frontend | Blade + Tailwind CSS |
| Charting | Chart.js |
| Deployment | Hostinger (Shared Hosting) |

### IoT Hardware
| Komponen | Spesifikasi |
|---|---|
| Mikrokontroler | ESP32 (CP2102) |
| Sensor | DHT22 (suhu & kelembapan) |
| Aktuator | Relay 2-channel |
| Pompa | Mini submersible water pump DC 5V + misting nozzle |
| Firmware | Arduino IDE |
| Library | DHT (Adafruit), ArduinoJson, WiFiManager (tzapu), WiFiClientSecure |

---

## 📡 API Endpoints

### Telemetry — ESP32 → Server

```http
POST /api/telemetry
Content-Type: application/json
X-DEVICE-KEY: {device_key}   (opsional jika device_key enclosure dikosongkan)
```

```json
{
  "enclosure_id": 1,
  "temperature": 25.4,
  "humidity": 84.7,
  "misting_status": true,
  "misting_duration_executed": 10,
  "device_timestamp": "2026-05-20T15:20:00+07:00"
}
```

---

### Ambil Konfigurasi — Server → ESP32

```http
GET /api/enclosures/{id}/control-config
```

```json
{
  "success": true,
  "data": {
    "enclosure_id": 1,
    "mode": "auto",
    "bottom_humidity": 82,
    "top_humidity": 92,
    "misting_duration_seconds": 10,
    "humidity_min": 80,
    "humidity_max": 95
  }
}
```

---

### Update Parameter (Manual dari Web)

```http
PUT /api/enclosures/{id}/parameters
```

```json
{
  "misting_bottom_threshold": 82,
  "misting_top_threshold": 92,
  "misting_duration_seconds": 10,
  "source": "manual"
}
```

---

### Apply / Reject Rekomendasi DSS

```http
POST /api/recommendations/{id}/apply
POST /api/recommendations/{id}/reject
```

---

## 🚀 Instalasi Lokal

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 8.0
- Python 3.x (untuk simulator)

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/arpojan/Monitoring-Enclosure.git
cd Monitoring-Enclosure

# 2. Install dependensi PHP
composer install

# 3. Install dependensi JS
npm install

# 4. Salin konfigurasi environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di .env
# (lihat bagian Konfigurasi di bawah)

# 7. Jalankan migrasi dan seeder
php artisan migrate --seed

# 8. Build assets
npm run dev

# 9. Jalankan server
php artisan serve
```

### Konfigurasi `.env`

```env
APP_NAME="RAP Enclosure DSS"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skripsi
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ **Perhatian untuk Hostinger:** Jangan set variabel environment melalui hPanel jika sudah dikonfigurasi di `.env` — hPanel dapat meng-override nilai `.env`. Gunakan `SetEnv` di `.htaccess` jika diperlukan.

---

## 🐍 Simulator ESP32

Untuk pengujian tanpa hardware fisik, gunakan simulator telemetry:

```bash
python telemetry_simulator.py
```

Simulator mengikuti flow lengkap: mengambil konfigurasi dari web, menjalankan rule-based misting secara lokal, lalu mengirim data telemetry dan status misting aktual ke backend.

---

## 📂 Struktur File Penting

```
app/
├── Http/Controllers/
│   ├── Api/
│   │   ├── TelemetryController.php      # Endpoint telemetry ESP32
│   │   ├── EnclosureController.php      # Manajemen enclosure & konfigurasi
│   │   └── RecommendationController.php # Apply/reject DSS recommendation
│   └── DashboardController.php
├── Models/
│   ├── AnimalKnowledgeBase.php          # 28 spesies + threshold
│   ├── EnclosureParameter.php           # Parameter misting aktif
│   └── ParameterHistory.php             # Riwayat perubahan parameter
├── Services/
│   └── DssService.php                   # Engine DSS Rule-Based + Stability Score
resources/
└── views/
    └── dashboard/
        └── index.blade.php              # Dashboard utama
public/assets/js/
├── api.js                               # Client-side API calls
└── app.js                               # Dashboard logic (polling real-time)
database/
├── migrations/
└── seeders/
telemetry_simulator.py                   # Simulator sensor ESP32
telemetry_simulator_2.py                 # Simulator alternatif
```

---

## 🧪 Pengujian

| Metode Pengujian | Hasil |
|---|---|
| **Black-Box Testing** (Equivalence Partitioning) | ✅ 100% Pass — 11 test case |
| **System Usability Scale (SUS)** | 83.50 / 100 — **Excellent (Grade A)** |
| Jumlah Responden | 15 responden |

---

## 📋 SDLC

Proyek dikembangkan menggunakan metode **Waterfall** dengan 5 fase:

```
1. Requirement   →   2. Design   →   3. Implementation
                                            ↓
                      5. Maintenance ← 4. Verification
```

Deployment ke Hostinger merupakan bagian dari fase **Maintenance**.

---

## 🔒 Keamanan API

Endpoint ESP32 mendukung header opsional untuk autentikasi perangkat:

```
X-DEVICE-KEY: your-device-key
```

- Jika `device_key` pada tabel `enclosures` **kosong** → API menerima request tanpa header (mode demo/lokal)
- Jika `device_key` **diisi** → ESP32 wajib menyertakan header tersebut

---

## 👨‍💻 Pengembang

**Arvauzan Putra Kurniawan**
NPM: 10122227
Program Studi Sistem Informasi — Universitas Gunadarma

Dosen Pembimbing: Dr. Suci Br Kembaren, S.Kom., M.M.S.I.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk keperluan akademik. Seluruh hak cipta milik pengembang dan Universitas Gunadarma.

---
