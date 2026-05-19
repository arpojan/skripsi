<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enclosure;
use App\Models\SensorLog;
use App\Services\MistingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TelemetryController extends Controller
{
    public function __construct(
        private MistingService $mistingService
    ) {}

    /**
     * Menerima data telemetry dari ESP32 atau simulator.
     *
     * Flow:
     * 1. Validasi payload (hanya sensor readings, tanpa misting_status)
     * 2. Verifikasi enclosure exists & aktif
     * 3. Backend menentukan misting ON/OFF (rule-based)
     * 4. Simpan ke sensor_logs (dengan misting hasil keputusan backend)
     * 5. Update heartbeat (last_seen_at)
     * 6. Return response JSON + misting command untuk ESP32
     */
    public function store(Request $request): JsonResponse
    {
        // 1. Validasi payload — ESP32 hanya kirim sensor readings
        $validated = $request->validate([
            'enclosure_id' => 'required|integer|exists:enclosures,id',
            'temperature'  => 'required|numeric|between:-10,60',
            'humidity'     => 'required|numeric|between:0,100',
        ]);

        // 2. Verifikasi enclosure aktif (eager load parameters untuk misting logic)
        $enclosure = Enclosure::with('parameters')->findOrFail($validated['enclosure_id']);

        if (!$enclosure->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Enclosure is not active',
            ], 422);
        }

        // 3. Backend rule-based misting decision
        $mistingCommand = $this->mistingService->evaluate(
            $enclosure,
            (float) $validated['humidity']
        );

        // 4. Simpan sensor log (misting_status dari keputusan backend)
        $now = Carbon::now();

        $sensorLog = SensorLog::create([
            'enclosure_id'  => $enclosure->id,
            'temperature'   => $validated['temperature'],
            'humidity'      => $validated['humidity'],
            'misting_status'=> $mistingCommand,
            'logged_at'     => $now,
        ]);

        // 5. Update heartbeat ESP32
        $enclosure->update(['last_seen_at' => $now]);

        // 6. Response dengan misting command untuk ESP32
        return response()->json([
            'success' => true,
            'message' => 'Telemetry received',
            'data'    => [
                'sensor_log_id'   => $sensorLog->id,
                'enclosure_id'    => $enclosure->id,
                'enclosure_name'  => $enclosure->name,
                'temperature'     => $sensorLog->temperature,
                'humidity'        => $sensorLog->humidity,
                'misting_command' => $mistingCommand,
                'logged_at'       => $sensorLog->logged_at->toIso8601String(),
                'system_status'   => 'online',
            ],
        ], 201);
    }
}
