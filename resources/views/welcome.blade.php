<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Enclosure Monitoring & Decision Support System</title>

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#f0f4f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="mobile-web-app-capable" content="yes">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons (Phosphor Icons) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body>
    <!-- Login Screen -->
    <div id="login-screen" class="view active">
        <div class="login-container">
            <div class="login-card glass-card">
                <div class="login-logo">
                    <i class="ph ph-leaf text-green"></i>
                </div>
                <h2>Sistem Kandang Pintar</h2>
                <p>Sistem Pendukung Keputusan</p>
                <form id="login-form">
                    <div class="input-group">
                        <i class="ph ph-envelope"></i>
                        <input type="email" placeholder="Email" required value="user1@testing.com">
                    </div>
                    <div class="input-group">
                        <i class="ph ph-lock"></i>
                        <input type="password" placeholder="Kata Sandi" required value="password123">
                    </div>
                    <button type="submit" class="btn-primary">Masuk</button>
                </form>
                <div class="login-footer">
                    <a href="#">Lupa kata sandi?</a>
                </div>
            </div>
            <div class="login-bg-decoration"></div>
        </div>
    </div>

    <!-- Enclosure Selection Screen -->
    <div id="enclosure-screen" class="view">
        <!-- Top Action Bar -->
        <div class="enclosure-topbar">
            <button id="enclosure-theme-toggle" class="btn-icon-pill" title="Ganti Tema">
                <i class="ph ph-moon"></i>
                <span>Tema</span>
            </button>
            <button id="enclosure-logout-btn" class="btn-icon-pill btn-logout" title="Keluar">
                <i class="ph ph-sign-out"></i>
                <span>Keluar</span>
            </button>
        </div>
        <div class="selection-container">
            <div class="selection-header">
                <div class="login-logo">
                    <i class="ph ph-leaf text-green"></i>
                </div>
                <h2>RAP Enclosure</h2>
                <p>Pilih kandang yang ingin Anda pantau hari ini</p>
            </div>
            <div class="enclosure-grid">
                <div class="enclosure-card glass-card" data-enclosure="1">
                    <div class="enclosure-icon">
                        <i class="ph ph-drop text-teal"></i>
                    </div>
                    <div class="enclosure-info">
                        <h3>Dart Frog Vivarium A</h3>
                        <div class="quick-stats"
                            style="display:flex; gap: 1rem; justify-content:center; font-size:0.9rem; color:var(--text-neutral); margin-bottom:0.75rem;">
                            <span><i class="ph ph-thermometer text-blue"></i> 24.5Â°C</span>
                            <span><i class="ph ph-drop text-teal"></i> 85%</span>
                        </div>
                        <span class="status-badge stable"><i class="ph ph-check-circle"></i> Stabil</span>
                    </div>
                </div>
                <div class="enclosure-card glass-card" data-enclosure="2">
                    <div class="enclosure-icon">
                        <i class="ph ph-thermometer text-blue"></i>
                    </div>
                    <div class="enclosure-info">
                        <h3>Dart Frog Vivarium B</h3>
                        <div class="quick-stats"
                            style="display:flex; gap: 1rem; justify-content:center; font-size:0.9rem; color:var(--text-neutral); margin-bottom:0.75rem;">
                            <span><i class="ph ph-thermometer text-blue"></i> 27.2Â°C</span>
                            <span><i class="ph ph-drop text-teal"></i> 65%</span>
                        </div>
                        <span class="status-badge warning"><i class="ph ph-warning"></i> Perhatian</span>
                    </div>
                </div>
                <div class="enclosure-card glass-card add-new">
                    <div class="enclosure-icon">
                        <i class="ph ph-plus text-neutral"></i>
                    </div>
                    <div class="enclosure-info">
                        <h3>Tambah Kandang Baru</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="login-bg-decoration"></div>
    </div>

    <!-- Main App Container -->
    <div id="app-screen" class="view">
        <!-- Sidebar Navigation -->
        <nav class="sidebar glass-card">
            <div class="sidebar-header">
                <i class="ph ph-leaf text-green"></i>
                <span class="brand">RAP Enclosure</span>
                <button class="menu-toggle d-mobile"><i class="ph ph-x"></i></button>
            </div>
            <ul class="nav-links">
                <li class="active" data-target="dashboard">
                    <i class="ph ph-squares-four"></i>
                    <span>Dasbor</span>
                </li>
                <li data-target="analytics">
                    <i class="ph ph-chart-line-up"></i>
                    <span>Analitik</span>
                </li>
                <li data-target="stability">
                    <i class="ph ph-scales"></i>
                    <span>Stabilitas</span>
                </li>
                <li id="back-enclosure"
                    style="margin-top: 20px; border-top: 1px solid var(--border-light); padding-top: 20px; border-radius: 0;">
                    <i class="ph ph-arrow-u-up-left"></i>
                    <span>Pilih Kandang</span>
                </li>
            </ul>
            <div class="sidebar-footer">
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=00b4d8&color=fff" alt="User">
                    <div class="user-info">
                        <span class="user-name">Admin</span>
                        <span class="user-role">Ahli Herpetologi</span>
                    </div>
                </div>
                <button id="logout-btn" class="btn-icon"><i class="ph ph-sign-out"></i></button>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="topbar">
                <div class="header-left">
                    <button class="menu-toggle d-mobile"><i class="ph ph-list"></i></button>
                    <h1 id="page-title">Dasbor</h1>
                </div>
                <div class="header-right">
                    <div class="status-indicator">
                        <span class="dot pulse-green"></span>
                        <span class="status-text">Sistem Aktif</span>
                    </div>
                    <button id="theme-toggle" class="btn-icon">
                        <i class="ph ph-moon"></i>
                    </button>
                    <button class="notification-btn btn-icon">
                        <i class="ph ph-bell"></i>
                        <span class="badge">2</span>
                    </button>
                </div>
            </header>

            <!-- Views Container -->
            <div class="views-container">

                <!-- View: Dashboard -->
                <div id="view-dashboard" class="page-view active">
                    <!-- Top Cards -->
                    <div class="metrics-grid">
                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Suhu</h3>
                                <i class="ph ph-thermometer text-blue"></i>
                            </div>
                            <div class="metric-value">24.5<span class="unit">Â°C</span></div>
                            <div class="metric-trend positive">
                                <i class="ph ph-trend-down"></i> -0.2Â°C dari jam lalu
                            </div>
                        </div>
                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Kelembapan</h3>
                                <i class="ph ph-drop text-teal"></i>
                            </div>
                            <div class="metric-value">85.2<span class="unit">%</span></div>
                            <div class="metric-trend positive">
                                <i class="ph ph-trend-up"></i> +1.5% dari jam lalu
                            </div>
                        </div>
                        <div class="metric-card glass-card" id="stability-metric-card">
                            <div class="metric-header">
                                <h3>Skor Stabilitas</h3>
                                <i class="ph ph-shield-check text-green" id="stability-icon"></i>
                            </div>
                            <div class="metric-value text-green" id="stability-score-value">92<span
                                    class="unit">/100</span></div>
                            <div class="metric-trend">
                                Status: <span class="text-green" id="stability-status-text">Stabil</span>
                            </div>
                        </div>
                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Sistem Pengabutan</h3>
                                <i class="ph ph-cloud-rain text-blue"></i>
                            </div>
                            <div class="metric-value">Siaga</div>
                            <div class="metric-trend text-neutral">
                                Siklus berikutnya ~2 jam
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <!-- Chart Area -->
                        <div class="chart-card glass-card grid-col-span-2">
                            <div class="card-header">
                                <h2>Kondisi Terkini</h2>
                                <div class="card-actions">
                                    <button class="btn-small active">1H</button>
                                    <button class="btn-small">24H</button>
                                </div>
                            </div>
                            <div class="chart-container">
                                <canvas id="realtimeChart"></canvas>
                            </div>
                        </div>

                        <!-- Environment Parameters (Moved from Settings) -->
                        <div class="settings-card glass-card" id="dashboard-env-params">
                            <div class="card-header">
                                <h2><i class="ph ph-sliders"></i> Parameter Lingkungan</h2>
                            </div>
                            <form class="settings-form">
                                <div class="form-group">
                                    <label>Ambang Batas Kelembapan Bawah (RH %)</label>
                                    <div class="range-slider-group">
                                        <input type="range" min="60" max="90" value="82" id="bottomRhSlider">
                                        <span class="range-value" id="bottomRhVal">82%</span>
                                    </div>
                                    <p class="helper-text">Sistem pengabutan akan aktif saat kelembapan turun di bawah
                                        nilai ini.</p>
                                </div>
                                <div class="form-group">
                                    <label>Target Kelembapan Atas (RH %)</label>
                                    <div class="range-slider-group">
                                        <input type="range" min="70" max="100" value="95" id="topRhSlider">
                                        <span class="range-value" id="topRhVal">95%</span>
                                    </div>
                                    <p class="helper-text">Tingkat kelembapan yang ditargetkan setelah siklus
                                        pengabutan.</p>
                                </div>
                                <div class="form-group">
                                    <label>Durasi Pengabutan (Detik)</label>
                                    <input type="number" class="input-modern" value="20" min="5" max="120">
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn-primary">Simpan Parameter</button>
                                    <button type="button" class="btn-secondary">Kembalikan ke Awal</button>
                                </div>
                            </form>
                        </div>



                        <!-- AI Insight Panel -->
                        <div class="insight-card glass-card grid-col-span-2">
                            <div class="card-header">
                                <h2><i class="ph ph-chart-scatter text-blue"></i> Analisis Korelasi</h2>
                            </div>
                            <div class="insight-list">
                                <div class="insight-item warning">
                                    <div class="insight-icon"><i class="ph ph-warning"></i></div>
                                    <div class="insight-content">
                                        <h4>Fluktuasi Kelembapan</h4>
                                        <p>Kelembapan terlalu fluktuatif pada malam hari (var > 5%).</p>
                                    </div>
                                </div>
                                <div class="insight-item info">
                                    <div class="insight-icon"><i class="ph ph-info"></i></div>
                                    <div class="insight-content">
                                        <h4>Durasi Pengabutan</h4>
                                        <p>Durasi pengabutan saat ini (15s) mungkin terlalu singkat untuk mencapai 90%
                                            RH.
                                        </p>
                                    </div>
                                </div>
                                <div class="insight-item success">
                                    <div class="insight-icon"><i class="ph ph-check-circle"></i></div>
                                    <div class="insight-content">
                                        <h4>Kondisi Ideal</h4>
                                        <p>Suhu sangat stabil dalam 24 jam terakhir.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notification Settings -->
                        <div class="settings-card glass-card">
                            <div class="card-header">
                                <h2><i class="ph ph-bell-ringing"></i> Notifikasi</h2>
                            </div>
                            <div class="toggle-list">
                                <div class="toggle-item">
                                    <div class="toggle-info">
                                        <h4>Peringatan Kelembapan Rendah</h4>
                                        <p>Beri tahu saat RH turun</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                                <div class="toggle-item">
                                    <div class="toggle-info">
                                        <h4>Kandang Tidak Stabil</h4>
                                        <p>Beri tahu saat skor < 70</p>
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Recommendation System -->
                        <div class="recommendation-card glass-card grid-col-span-3">
                            <div class="card-header">
                                <h2><i class="ph ph-lightbulb text-warning"></i> Rekomendasi Tindakan</h2>
                            </div>
                            <div class="recommendation-content">
                                <div class="recommendation-text">
                                    <p>Berdasarkan fluktuasi kelembapan terbaru, AI menyarankan penyesuaian parameter
                                        pengabutan untuk menjaga stabilitas lingkungan kandang Katak Anda.</p>
                                </div>
                                <div class="recommendation-params">
                                    <div class="param-box">
                                        <span class="param-label">RH Bawah</span>
                                        <span class="param-val">80% <i class="ph ph-arrow-right"></i> 82%</span>
                                    </div>
                                    <div class="param-box">
                                        <span class="param-label">RH Atas</span>
                                        <span class="param-val">90% <i class="ph ph-arrow-right"></i> 95%</span>
                                    </div>
                                    <div class="param-box">
                                        <span class="param-label">Pengabutan</span>
                                        <span class="param-val">15s <i class="ph ph-arrow-right"></i> 20s</span>
                                    </div>
                                </div>
                                <div class="recommendation-actions">
                                    <button class="btn-primary"><i class="ph ph-check"></i> Terapkan
                                        Rekomendasi</button>
                                    <button class="btn-secondary"><i class="ph ph-pencil-simple"></i> Ubah
                                        Manual</button>
                                    <button class="btn-text">Abaikan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View: Analytics -->
                <div id="view-analytics" class="page-view">
                    <div class="analytics-header glass-card">
                        <div class="filter-group">
                            <label>Rentang Waktu</label>
                            <input type="date" class="input-modern" value="2026-05-03">
                            <span>-</span>
                            <input type="date" class="input-modern" value="2026-05-10">
                        </div>
                        <div class="filter-group">
                            <label>Pilih Metrik</label>
                            <select class="input-modern">
                                <option>Skor Stabilitas</option>
                                <option>Kelembapan (RH %)</option>
                                <option>Suhu (Â°C)</option>
                            </select>
                        </div>
                        <button class="btn-secondary"><i class="ph ph-download-simple"></i> Ekspor Laporan</button>
                    </div>

                    <div class="chart-card glass-card mb-2">
                        <div class="card-header">
                            <h2>Data Historis Suhu & Kelembapan</h2>
                        </div>
                        <div class="chart-container large">
                            <canvas id="historicalChart"></canvas>
                        </div>
                    </div>

                    <div class="dashboard-grid">
                        <div class="chart-card glass-card grid-col-span-3">
                            <div class="card-header">
                                <h2>Analisis Tren Stabilitas</h2>
                            </div>
                            <div class="chart-container">
                                <canvas id="stabilityTrendChart"></canvas>
                            </div>
                        </div>
                        <div class="event-card glass-card">
                            <div class="card-header">
                                <h2>Linimasa Kejadian</h2>
                            </div>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-dot system"></div>
                                    <div class="timeline-time">Hari ini, 14:30</div>
                                    <div class="timeline-desc">Sistem pengabutan diaktifkan (20s)</div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot warning"></div>
                                    <div class="timeline-time">Hari ini, 14:15</div>
                                    <div class="timeline-desc">Kelembapan turun di bawah 80%</div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot user"></div>
                                    <div class="timeline-time">Kemarin, 09:00</div>
                                    <div class="timeline-desc">Pengguna menerapkan rekomendasi AI</div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot system"></div>
                                    <div class="timeline-time">Kemarin, 08:30</div>
                                    <div class="timeline-desc">Status kandang berubah menjadi Stabil</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- View: Stability Analysis -->
                <div id="view-stability" class="page-view">
                    <div class="dashboard-grid">
                        <div class="stability-hero glass-card grid-col-span-3">
                            <div class="hero-content">
                                <div class="score-gauge-container">
                                    <canvas id="scoreGaugeChart"></canvas>
                                    <div class="gauge-center">
                                        <span class="score-value text-green">92</span>
                                        <span class="score-label">Sangat Baik</span>
                                    </div>
                                </div>
                                <div class="hero-details">
                                    <h2>Stabilitas Lingkungan</h2>
                                    <p>Sistem ini mengevaluasi kestabilan lingkungan berdasarkan kesesuaian range (Range
                                        Compliance), tingkat fluktuasi (Variability), dan durasi kondisi ideal
                                        (Stability Duration).</p>
                                    <div class="status-badge stable">
                                        <i class="ph ph-check-circle"></i> Status: Habitat Optimal
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Kepatuhan Range (RC)</h3>
                                <i class="ph ph-target text-teal"></i>
                            </div>
                            <div class="metric-value">95<span class="unit">%</span></div>
                            <div class="metric-trend text-neutral">Waktu dalam ambang ideal</div>
                            <div class="progress-bar-container mt-1">
                                <div class="progress-bar bg-teal" style="width: 95%"></div>
                            </div>
                        </div>

                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Variabilitas</h3>
                                <i class="ph ph-wave-sine text-warning"></i>
                            </div>
                            <div class="metric-value">Rendah</div>
                            <div class="metric-trend text-neutral">Deviasi dari rata-rata</div>
                            <div class="progress-bar-container mt-1">
                                <div class="progress-bar bg-warning" style="width: 25%"></div>
                            </div>
                        </div>

                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Durasi Stabilitas</h3>
                                <i class="ph ph-hourglass text-blue"></i>
                            </div>
                            <div class="metric-value">14<span class="unit">j</span></div>
                            <div class="metric-trend text-neutral">Jam stabil berturut-turut</div>
                            <div class="progress-bar-container mt-1">
                                <div class="progress-bar bg-blue" style="width: 70%"></div>
                            </div>
                        </div>

                        <div class="metric-card glass-card">
                            <div class="metric-header">
                                <h3>Fluctuation Penalty</h3>
                                <i class="ph ph-trend-down text-red"></i>
                            </div>
                            <div class="metric-value">-3<span class="unit">pts</span></div>
                            <div class="metric-trend text-neutral">Deductions due to rapid spikes</div>
                            <div class="progress-bar-container mt-1">
                                <div class="progress-bar bg-red" style="width: 15%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
    </div>

    </div>
    </main>
    </div>

    <!-- Notification Toast -->
    <div class="notification-toast glass-card" id="notificationToast">
        <div class="toast-icon"><i class="ph ph-warning-circle text-warning"></i></div>
        <div class="toast-content">
            <h4>Rekomendasi Baru Tersedia</h4>
            <p>AI telah menganalisis tren terbaru. Tinjau parameter pengabutan baru.</p>
        </div>
        <button class="close-toast"><i class="ph ph-x"></i></button>
    </div>

    <!-- Recommendation Modal -->
    <div id="recommendation-modal" class="modal">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h2><i class="ph ph-warning text-warning"></i> Peringatan Stabilitas</h2>
                <button class="close-modal"><i class="ph ph-x"></i></button>
            </div>
            <div class="modal-body">
                <p><strong>Peringatan!</strong> Skor stabilitas saat ini buruk. Kondisi kandang membutuhkan perhatian.
                </p>
                <div class="recommendation-content mt-2">
                    <p>AI menyarankan penyesuaian parameter pengabutan untuk menjaga stabilitas kandang Katak Anda.</p>
                    <div class="recommendation-params">
                        <div class="param-box">
                            <span class="param-label">RH Bawah</span>
                            <span class="param-val">80% <i class="ph ph-arrow-right"></i> 82%</span>
                        </div>
                        <div class="param-box">
                            <span class="param-label">RH Atas</span>
                            <span class="param-val">90% <i class="ph ph-arrow-right"></i> 95%</span>
                        </div>
                        <div class="param-box">
                            <span class="param-label">Pengabutan</span>
                            <span class="param-val">15s <i class="ph ph-arrow-right"></i> 20s</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer recommendation-actions">
                <button class="btn-primary" id="apply-recommendation-btn"><i class="ph ph-check"></i> Terapkan</button>
                <button class="btn-secondary close-modal"><i class="ph ph-pencil-simple"></i> Ubah Manual</button>
                <button class="btn-text close-modal">Abaikan</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset("service-worker.js") }}')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.error('Service Worker registration failed', err));
            });
        }
    </script>
</body>

</html>
