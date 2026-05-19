document.addEventListener('DOMContentLoaded', () => {

    // --- State & Navigation ---
    const loginForm = document.getElementById('login-form');
    const loginScreen = document.getElementById('login-screen');
    const enclosureScreen = document.getElementById('enclosure-screen');
    const enclosureCards = document.querySelectorAll('.enclosure-card:not(.add-new)');
    const appScreen = document.getElementById('app-screen');
    const logoutBtn = document.getElementById('logout-btn');

    const navLinks = document.querySelectorAll('.nav-links li');
    const pageViews = document.querySelectorAll('.page-view');
    const pageTitle = document.getElementById('page-title');

    const menuToggles = document.querySelectorAll('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');

    // Chart instances
    let charts = {};

    // Pengecekan agar tidak error jika tidak ada di halaman login
    if (loginForm) {
        // Login Handle
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            loginScreen.classList.remove('active');
            setTimeout(() => {
                if (enclosureScreen) enclosureScreen.classList.add('active');
            }, 300);
        });
    }

    // Enclosure Selection
    if (enclosureCards.length > 0) {
        enclosureCards.forEach(card => {
            card.addEventListener('click', () => {
                if (enclosureScreen) enclosureScreen.classList.remove('active');
                const enclosureId = card.getAttribute('data-enclosure');

                setTimeout(() => {
                    if (appScreen) appScreen.classList.add('active');
                    initCharts();

                    if (enclosureId === '2') {
                        setTimeout(simulateScoreDrop, 3000);
                    } else {
                        setStableState();
                    }
                }, 300);
            });
        });
    }

    // Logout Handle (Untuk versi HTML lama, versi Laravel sudah pakai Form di Blade)
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            if (appScreen) appScreen.classList.remove('active');
            setTimeout(() => {
                if (loginScreen) loginScreen.classList.add('active');
                Object.values(charts).forEach(c => c.destroy());
                charts = {};
            }, 300);
        });
    }

    // Navigation Handle
    if (navLinks.length > 0) {
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                // PERBAIKAN: Jika li mengandung tag <a> (misal rute Laravel Pilih Kandang / Logout), 
                // biarkan browser yang melakukan perpindahan halaman secara bawaan.
                if (link.querySelector('a')) {
                    return;
                }

                if (link.id === 'back-enclosure') {
                    if (appScreen) appScreen.classList.remove('active');
                    setTimeout(() => {
                        if (enclosureScreen) enclosureScreen.classList.add('active');
                        Object.values(charts).forEach(c => c.destroy());
                        charts = {};
                    }, 300);

                    if (window.innerWidth <= 768 && sidebar) {
                        sidebar.classList.remove('open');
                    }
                    return;
                }

                // Update active state in nav
                navLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Update title
                const target = link.getAttribute('data-target');
                if (pageTitle && link.querySelector('span')) {
                    pageTitle.innerText = link.querySelector('span').innerText;
                }

                // Show target view
                pageViews.forEach(view => {
                    view.classList.remove('active');
                    if (view.id === `view-${target}`) {
                        view.classList.add('active');
                    }
                });

                // Close mobile menu if open
                if (window.innerWidth <= 768 && sidebar) {
                    sidebar.classList.remove('open');
                }
            });
        });
    }

    // Mobile Menu Toggle
    if (menuToggles.length > 0 && sidebar) {
        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        });
    }

    // Range Sliders update values
    const sliders = [
        { id: 'bottomRhSlider', valId: 'bottomRhVal' },
        { id: 'topRhSlider', valId: 'topRhVal' }
    ];

    sliders.forEach(s => {
        const slider = document.getElementById(s.id);
        const val = document.getElementById(s.valId);
        if (slider && val) {
            slider.addEventListener('input', (e) => {
                val.innerText = e.target.value + '%';
            });
        }
    });

    // Toast Notification & Modal Simulation
    const toast = document.getElementById('notificationToast');
    const closeToast = document.querySelector('.close-toast');
    const modal = document.getElementById('recommendation-modal');
    const closeBtns = document.querySelectorAll('.close-modal');
    const applyBtn = document.getElementById('apply-recommendation-btn');

    function showNotificationToast(title, msg) {
        if (toast && title && msg) {
            toast.querySelector('.toast-content h4').innerText = title;
            toast.querySelector('.toast-content p').innerText = msg;
            setTimeout(() => {
                toast.classList.add('show');
            }, 500);
        }
    }

    if (closeToast && toast) {
        closeToast.addEventListener('click', () => {
            toast.classList.remove('show');
        });
    }

    if (closeBtns.length > 0) {
        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (modal) modal.classList.remove('active');
            });
        });
    }

    if (applyBtn) {
        applyBtn.addEventListener('click', () => {
            if (modal) modal.classList.remove('active');
            restoreStabilityScore();
        });
    }

    function simulateScoreDrop() {
        const scoreVal = document.getElementById('stability-score-value');
        const statusText = document.getElementById('stability-status-text');
        const cardIcon = document.getElementById('stability-icon');

        if (scoreVal && statusText) {
            scoreVal.innerHTML = '65<span class="unit">/100</span>';
            scoreVal.className = 'metric-value text-red';
            statusText.innerText = 'Peringatan';
            statusText.className = 'text-red';
            if (cardIcon) {
                cardIcon.className = 'ph ph-warning-circle text-red';
            }
            if (modal) {
                modal.classList.add('active');
            }
        }
    }

    function setStableState() {
        const scoreVal = document.getElementById('stability-score-value');
        const statusText = document.getElementById('stability-status-text');
        const cardIcon = document.getElementById('stability-icon');

        if (scoreVal && statusText) {
            scoreVal.innerHTML = '92<span class="unit">/100</span>';
            scoreVal.className = 'metric-value text-green';
            statusText.innerText = 'Stabil';
            statusText.className = 'text-green';
            if (cardIcon) {
                cardIcon.className = 'ph ph-shield-check text-green';
            }
            if (modal) {
                modal.classList.remove('active');
            }
        }
    }

    function restoreStabilityScore() {
        const scoreVal = document.getElementById('stability-score-value');
        const statusText = document.getElementById('stability-status-text');
        const cardIcon = document.getElementById('stability-icon');

        if (scoreVal && statusText) {
            scoreVal.innerHTML = '94<span class="unit">/100</span>';
            scoreVal.className = 'metric-value text-green';
            statusText.innerText = 'Stabil (Diterapkan)';
            statusText.className = 'text-green';
            if (cardIcon) {
                cardIcon.className = 'ph ph-shield-check text-green';
            }
            showNotificationToast('Rekomendasi Diterapkan', 'Parameter kandang berhasil diperbarui.');
        }
    }

    // --- Chart.js Configuration ---
    if (typeof Chart !== 'undefined') {
        Chart.defaults.font.family = "'Outfit', sans-serif";
    }

    let commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8
                }
            },
            tooltip: {
                backgroundColor: 'rgba(255, 255, 255, 0.95)',
                titleColor: '#1f2924',
                bodyColor: '#4a5d54',
                borderColor: 'rgba(42, 157, 143, 0.15)',
                borderWidth: 1,
                padding: 10,
                boxPadding: 4
            }
        },
        scales: {
            x: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            },
            y: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                }
            }
        }
    };

    // --- Theme Toggle ---
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector('i') : null;

    function updateChartColors(theme) {
        if (typeof Chart === 'undefined') return;

        const isDark = theme === 'dark';
        Chart.defaults.color = isDark ? '#a4b8ad' : '#4a5d54';

        const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
        const tooltipBg = isDark ? 'rgba(18, 28, 24, 0.95)' : 'rgba(255, 255, 255, 0.95)';
        const tooltipTitle = isDark ? '#f0f4f1' : '#1f2924';
        const tooltipBody = isDark ? '#a4b8ad' : '#4a5d54';

        if (commonOptions.plugins) {
            commonOptions.plugins.tooltip.backgroundColor = tooltipBg;
            commonOptions.plugins.tooltip.titleColor = tooltipTitle;
            commonOptions.plugins.tooltip.bodyColor = tooltipBody;
        }
        if (commonOptions.scales) {
            if (commonOptions.scales.x) commonOptions.scales.x.grid.color = gridColor;
            if (commonOptions.scales.y) commonOptions.scales.y.grid.color = gridColor;
        }

        Object.values(charts).forEach(chart => {
            if (chart.options.scales) {
                if (chart.options.scales.x) chart.options.scales.x.grid.color = gridColor;
                if (chart.options.scales.y) chart.options.scales.y.grid.color = gridColor;
                if (chart.options.scales.y1) chart.options.scales.y1.grid.color = gridColor;
            }
            if (chart.options.plugins && chart.options.plugins.tooltip) {
                chart.options.plugins.tooltip.backgroundColor = tooltipBg;
                chart.options.plugins.tooltip.titleColor = tooltipTitle;
                chart.options.plugins.tooltip.bodyColor = tooltipBody;
            }
            if (chart.config.type === 'doughnut' && chart.data.datasets.length > 0) {
                chart.data.datasets[0].backgroundColor[1] = gridColor;
            }
            chart.update();
        });
    }

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'ph ph-sun' : 'ph ph-moon';
        }
        updateChartColors(theme);

        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', theme === 'dark' ? '#0c1411' : '#f0f4f1');
        }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    }

    function initCharts() {
        if (typeof Chart === 'undefined') return; // Cek jika Chart.js belum di-load

        const ctxRealtime = document.getElementById('realtimeChart');
        if (ctxRealtime) {
            charts.realtime = new Chart(ctxRealtime, {
                type: 'line',
                data: {
                    labels: ['10:00', '10:10', '10:20', '10:30', '10:40', '10:50', '11:00'],
                    datasets: [
                        {
                            label: 'Kelembapan (%)',
                            data: [82, 81.5, 81, 80.5, 95, 92, 85.2],
                            borderColor: '#457b9d',
                            backgroundColor: 'rgba(69, 123, 157, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Suhu (°C)',
                            data: [24.2, 24.3, 24.4, 24.5, 24.3, 24.4, 24.5],
                            borderColor: '#4caf50',
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            tension: 0.4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        x: commonOptions.scales.x,
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: { display: true, text: 'Humidity (%)' },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: { display: true, text: 'Temp (°C)' },
                            grid: { drawOnChartArea: false }
                        }
                    }
                }
            });
        }

        const ctxHist = document.getElementById('historicalChart');
        if (ctxHist) {
            charts.historical = new Chart(ctxHist, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        {
                            label: 'Avg Humidity (%)',
                            data: [84, 85, 83, 86, 88, 85, 84],
                            borderColor: '#457b9d',
                            tension: 0.3,
                            borderWidth: 2
                        },
                        {
                            label: 'Rata-rata Suhu (°C)',
                            data: [24.1, 24.5, 24.8, 24.4, 24.2, 24.5, 24.6],
                            borderColor: '#4caf50',
                            tension: 0.3,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    ...commonOptions
                }
            });
        }

        const ctxStabilityTrend = document.getElementById('stabilityTrendChart');
        if (ctxStabilityTrend) {
            charts.stabilityTrend = new Chart(ctxStabilityTrend, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: 'Skor Stabilitas',
                        data: [75, 82, 80, 88, 95, 90, 92],
                        borderColor: '#e9c46a',
                        backgroundColor: 'rgba(233, 196, 106, 0.2)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            min: 0,
                            max: 100,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    }
                }
            });
        }

        const ctxGauge = document.getElementById('scoreGaugeChart');
        if (ctxGauge) {
            charts.gauge = new Chart(ctxGauge, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [92, 8],
                        backgroundColor: [
                            '#4caf50',
                            'rgba(0, 0, 0, 0.05)'
                        ],
                        borderWidth: 0,
                        cutout: '80%',
                        circumference: 270,
                        rotation: 225
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        }
    }

    // PERBAIKAN: Jika canvas grafik ada saat halaman dimuat (artinya sedang di halaman Dasbor), inisiasi langsung
    if (document.getElementById('realtimeChart')) {
        initCharts();
    }
});