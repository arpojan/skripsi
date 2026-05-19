"""
Smart Enclosure — ESP32 Telemetry Simulator
============================================

Simulasi ESP32 yang mengirim data sensor ke Laravel backend.
Simulator ini TIDAK menentukan misting sendiri — backend yang memutuskan.

Flow:
    Simulator kirim telemetry (temp + humidity)
    → Laravel evaluasi rule-based misting
    → Laravel return misting_command
    → Simulator menjalankan command (simulasi relay ON/OFF)
    → Humidity berubah sesuai command

Cara menjalankan:
    python telemetry_simulator.py

Pastikan Laravel server sudah berjalan:
    php artisan serve
"""

import requests
import random
import time
import sys
from datetime import datetime

# ─── Konfigurasi ──────────────────────────────────────────────
API_URL = "http://localhost:8000/api/telemetry"
ENCLOSURE_ID = 1
INTERVAL = 10  # detik

# ─── State Simulasi ───────────────────────────────────────────
current_temp = 25.0
current_humidity = 85.0
misting_active = False  # Ditentukan oleh backend, bukan simulator


def simulate_environment():
    """
    Simulasikan perubahan lingkungan enclosure secara realistis.

    Simulator hanya mensimulasikan EFEK FISIK dari misting:
    - Misting ON (dari backend)  → humidity naik
    - Misting OFF (dari backend) → humidity turun natural

    Simulator TIDAK memutuskan kapan misting ON/OFF.
    Keputusan itu datang dari backend response.
    """
    global current_temp, current_humidity, misting_active

    # ── Temperature: drift kecil (±0.3°C) ──
    temp_drift = random.uniform(-0.3, 0.3)
    current_temp = max(23.0, min(27.0, current_temp + temp_drift))

    # ── Humidity: berubah berdasarkan misting command dari backend ──
    if misting_active:
        # Misting relay ON → humidity naik secara gradual
        humidity_change = random.uniform(1.0, 3.0)
        current_humidity += humidity_change
    else:
        # Misting relay OFF → humidity turun natural (evaporasi)
        humidity_drop = random.uniform(0.5, 1.5)
        current_humidity -= humidity_drop

    # Clamp ke range fisik realistis
    current_humidity = max(70.0, min(99.0, current_humidity))

    return {
        "enclosure_id": ENCLOSURE_ID,
        "temperature": round(current_temp, 2),
        "humidity": round(current_humidity, 2),
    }


def send_telemetry(payload):
    """Kirim telemetry ke Laravel API dan terima misting command."""
    try:
        response = requests.post(API_URL, json=payload, timeout=5)
        return response.status_code, response.json()
    except requests.exceptions.ConnectionError:
        return None, {"error": "Connection refused — pastikan Laravel server berjalan (php artisan serve)"}
    except requests.exceptions.Timeout:
        return None, {"error": "Request timeout"}
    except Exception as e:
        return None, {"error": str(e)}


def process_backend_command(response_data):
    """
    Proses misting command dari backend.
    Simulator bertindak sebagai relay — menjalankan apapun yang backend perintahkan.
    """
    global misting_active

    new_command = response_data.get("misting_command", False)
    old_status = misting_active

    misting_active = new_command

    # Log perubahan status misting
    if new_command and not old_status:
        print(f"  💧 BACKEND COMMAND: Misting ON")
    elif not new_command and old_status:
        print(f"  ✅ BACKEND COMMAND: Misting OFF")


def main():
    print("=" * 65)
    print("🦎 Smart Enclosure — ESP32 Telemetry Simulator")
    print("   Backend-Centric Rule-Based Control")
    print("=" * 65)
    print(f"  API URL      : {API_URL}")
    print(f"  Enclosure ID : {ENCLOSURE_ID}")
    print(f"  Interval     : {INTERVAL}s")
    print(f"  Misting      : Ditentukan oleh backend")
    print("=" * 65)
    print()

    tick = 0
    while True:
        tick += 1
        timestamp = datetime.now().strftime("%H:%M:%S")

        # 1. Simulasikan pembacaan sensor
        payload = simulate_environment()

        # 2. Kirim telemetry ke backend
        status_code, response = send_telemetry(payload)

        # 3. Proses response
        if status_code == 201:
            data = response["data"]

            # Jalankan misting command dari backend
            process_backend_command(data)

            misting_icon = "💧" if misting_active else "  "
            print(
                f"[{timestamp}] #{tick:04d}  "
                f"🌡 {payload['temperature']:5.2f}°C  "
                f"💦 {payload['humidity']:5.2f}%  "
                f"{misting_icon}  "
                f"→ ✅ OK (log: {data['sensor_log_id']})"
            )
        elif status_code is not None:
            print(
                f"[{timestamp}] #{tick:04d}  "
                f"🌡 {payload['temperature']:5.2f}°C  "
                f"💦 {payload['humidity']:5.2f}%  "
                f"→ ❌ HTTP {status_code}: {response.get('message', 'Error')}"
            )
        else:
            print(
                f"[{timestamp}] #{tick:04d}  "
                f"→ ⚠️  {response.get('error', 'Unknown error')}"
            )

        # 4. Tunggu interval
        time.sleep(INTERVAL)


if __name__ == "__main__":
    try:
        main()
    except KeyboardInterrupt:
        print("\n\n🛑 Simulator dihentikan.")
        sys.exit(0)
