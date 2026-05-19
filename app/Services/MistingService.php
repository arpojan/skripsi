<?php

namespace App\Services;

use App\Models\Enclosure;

/**
 * MistingService — Rule-Based Misting Control
 *
 * Pusat keputusan misting ON/OFF berdasarkan enclosure_parameters.
 * Dipisahkan dari controller agar logic bisa di-reuse oleh:
 * - TelemetryController (real-time decision)
 * - Analytics Engine (historical analysis)
 * - Future: Scheduled health checks
 *
 * Logic:
 *   humidity <= misting_bottom_threshold → ON
 *   humidity >= misting_top_threshold   → OFF
 *   otherwise                           → retain last state
 */
class MistingService
{
    /**
     * Tentukan apakah misting harus ON atau OFF.
     *
     * @param  Enclosure  $enclosure  Enclosure dengan parameters loaded
     * @param  float      $humidity   Humidity saat ini dari sensor
     * @return bool       true = misting ON, false = misting OFF
     */
    public function evaluate(Enclosure $enclosure, float $humidity): bool
    {
        $params = $enclosure->parameters;

        // Jika tidak ada parameter atau misting mode manual → OFF
        if (!$params || !$params->is_misting_auto) {
            return false;
        }

        $bottomThreshold = (float) $params->misting_bottom_threshold;
        $topThreshold    = (float) $params->misting_top_threshold;

        // Ambil status misting terakhir dari sensor log terbaru
        $lastMistingStatus = $this->getLastMistingStatus($enclosure);

        // Rule-based decision
        if ($humidity <= $bottomThreshold) {
            // RH terlalu rendah → misting ON
            return true;
        }

        if ($humidity >= $topThreshold) {
            // RH sudah cukup tinggi → misting OFF
            return false;
        }

        // Di antara threshold → pertahankan status sebelumnya (hysteresis)
        return $lastMistingStatus;
    }

    /**
     * Ambil status misting terakhir dari sensor_logs.
     * Hysteresis: mencegah toggling cepat di area antara threshold.
     */
    private function getLastMistingStatus(Enclosure $enclosure): bool
    {
        $lastLog = $enclosure->sensorLogs()
            ->latest('logged_at')
            ->first();

        return $lastLog ? (bool) $lastLog->misting_status : false;
    }
}
