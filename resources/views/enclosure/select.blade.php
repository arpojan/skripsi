<!-- resources/views/enclosure/select.blade.php -->
@extends('layouts.app')

@section('content')
<div class="view active">
    <!-- Top Action Bar -->
    <div class="enclosure-topbar">
        <button id="enclosure-theme-toggle" class="btn-icon-pill" title="Ganti Tema">
            <i class="ph ph-moon"></i>
            <span>Tema</span>
        </button>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn-icon-pill btn-logout" title="Keluar">
                <i class="ph ph-sign-out"></i>
                <span>Keluar</span>
            </button>
        </form>
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
            <!-- Tambahkan tag <a> untuk pindah halaman atau biarkan div dengan JS redirect -->
            @foreach ($enclosures as $enclosure)
                <!-- @if($enclosure->user_id === Auth::id()) -->

                <div class="enclosure-card glass-card" style="position: relative;">
                    <form action="{{ route('enclosure.select.post') }}" method="POST" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 1; margin: 0;">
                        @csrf
                        <input type="hidden" name="enclosure_id" value="1">
                        <button type="submit" style="width: 100%; height: 100%; background: transparent; border: none; cursor: pointer;"></button>
                    </form>
                    <button type="button" class="btn-icon edit-enclosure-btn" data-id="1" data-name="Dart Frog Vivarium A" style="position: absolute; top: 15px; right: 15px; z-index: 10;" title="Pengaturan Kandang">
                        <i class="ph ph-gear"></i>
                    </button>
                    <div class="enclosure-icon" style="position: relative; z-index: 2; pointer-events: none;">
                        <i class="ph ph-drop text-teal"></i>
                    </div>
                    <div class="enclosure-info" style="position: relative; z-index: 2; pointer-events: none;">
                        <h3>{{ $enclosure->name }}</h3>
                        <div class="quick-stats" style="display:flex; gap: 1rem; justify-content:center; font-size:0.9rem; color:var(--text-neutral); margin-bottom:0.75rem;">
                            <span><i class="ph ph-thermometer text-blue"></i> 24.5°C</span>
                            <span><i class="ph ph-drop text-teal"></i> 85%</span>
                        </div>
                        <span class="status-badge stable"><i class="ph ph-check-circle"></i> Stabil</span>
                    </div>
                </div>

                <!-- @endif -->
            @endforeach

            <!-- <div class="enclosure-card glass-card" style="position: relative;">
                <form action="{{ route('enclosure.select.post') }}" method="POST" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 1; margin: 0;">
                    @csrf
                    <input type="hidden" name="enclosure_id" value="{{ $enclosure->id }}">
                    <button type="submit" style="width: 100%; height: 100%; background: transparent; border: none; cursor: pointer;"></button>
                </form>
                <button type="button" class="btn-icon edit-enclosure-btn" data-id="1" data-name="Dart Frog Vivarium A" style="position: absolute; top: 15px; right: 15px; z-index: 10;" title="Pengaturan Kandang">
                    <i class="ph ph-gear"></i>
                </button>
                <div class="enclosure-icon" style="position: relative; z-index: 2; pointer-events: none;">
                    <i class="ph ph-drop text-teal"></i>
                </div>
                <div class="enclosure-info" style="position: relative; z-index: 2; pointer-events: none;">
                    <h3>Dart Frog Vivarium A</h3>
                    <div class="quick-stats" style="display:flex; gap: 1rem; justify-content:center; font-size:0.9rem; color:var(--text-neutral); margin-bottom:0.75rem;">
                        <span><i class="ph ph-thermometer text-blue"></i> 24.5°C</span>
                        <span><i class="ph ph-drop text-teal"></i> 85%</span>
                    </div>
                    <span class="status-badge stable"><i class="ph ph-check-circle"></i> Stabil</span>
                </div>
            </div>

            <div class="enclosure-card glass-card" style="position: relative;">
                <form action="{{ route('enclosure.select.post') }}" method="POST" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 1; margin: 0;">
                    @csrf
                    <input type="hidden" name="enclosure_id" value="2">
                    <button type="submit" style="width: 100%; height: 100%; background: transparent; border: none; cursor: pointer;"></button>
                </form>
                <button type="button" class="btn-icon edit-enclosure-btn" data-id="2" data-name="Dart Frog Vivarium B" style="position: absolute; top: 15px; right: 15px; z-index: 10;" title="Pengaturan Kandang">
                    <i class="ph ph-gear"></i>
                </button>
                <div class="enclosure-icon" style="position: relative; z-index: 2; pointer-events: none;">
                    <i class="ph ph-thermometer text-blue"></i>
                </div>
                <div class="enclosure-info" style="position: relative; z-index: 2; pointer-events: none;">
                    <h3>Dart Frog Vivarium B</h3>
                    <div class="quick-stats" style="display:flex; gap: 1rem; justify-content:center; font-size:0.9rem; color:var(--text-neutral); margin-bottom:0.75rem;">
                        <span><i class="ph ph-thermometer text-blue"></i> 27.2°C</span>
                        <span><i class="ph ph-drop text-teal"></i> 65%</span>
                    </div>
                    <span class="status-badge warning"><i class="ph ph-warning"></i> Perhatian</span>
                </div>
            </div> -->

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

    <!-- Edit Enclosure Modal -->
    <div id="edit-enclosure-modal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(5px);">
        <div class="modal-content glass-card" style="width: 90%; max-width: 400px; padding: 2rem; position: relative;">
            <button type="button" id="close-edit-modal" class="btn-icon" style="position: absolute; top: 1.5rem; right: 1.5rem;"><i class="ph ph-x"></i></button>
            <h2 style="margin-bottom: 1.5rem; font-size: 1.5rem;"><i class="ph ph-pencil text-teal"></i> Pengaturan Kandang</h2>
            <form action="#" method="POST" id="edit-enclosure-form">
                @csrf
                <input type="hidden" name="enclosure_id" id="edit-enclosure-id">
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">Nama Kandang</label>
                    <div class="input-group" style="margin-bottom: 0;">
                        <i class="ph ph-tag"></i>
                        <input type="text" name="name" id="edit-enclosure-name" style="width: 100%;">
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%;" id="save-enclosure-btn">
                    <i class="ph ph-check"></i> <span>Simpan Perubahan</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('enclosure-theme-toggle');
    const themeIcon = themeToggleBtn ? themeToggleBtn.querySelector('i') : null;

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'ph ph-sun' : 'ph ph-moon';
        }
        const metaThemeColor = document.querySelector('meta[name="theme-color"]');
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', theme === 'dark' ? '#0c1411' : '#f0f4f1');
        }
    }

    // Load saved theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    // Modal Edit Enclosure Logic
    const editBtns = document.querySelectorAll('.edit-enclosure-btn');
    const editModal = document.getElementById('edit-enclosure-modal');
    const closeEditBtn = document.getElementById('close-edit-modal');
    const inputName = document.getElementById('edit-enclosure-name');
    const inputId = document.getElementById('edit-enclosure-id');
    const enclosureForm = document.getElementById('edit-enclosure-form');

    // Simpan referensi ke tombol gear yang sedang aktif
    let activeGearBtn = null;

    if (editBtns.length > 0 && editModal) {
        editBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Simpan referensi tombol yang diklik
                activeGearBtn = btn;

                const encId = btn.getAttribute('data-id');
                const encName = btn.getAttribute('data-name');

                if (inputId) inputId.value = encId;
                if (inputName) inputName.value = encName;

                editModal.style.display = 'flex';
            });
        });

        if (closeEditBtn) {
            closeEditBtn.addEventListener('click', () => {
                editModal.style.display = 'none';
            });
        }

        editModal.addEventListener('click', (e) => {
            if (e.target === editModal) {
                editModal.style.display = 'none';
            }
        });
    }

    // ─── Handler Submit Form Edit Enclosure ──────────────────────
    if (enclosureForm) {
        enclosureForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const saveBtn = document.getElementById('save-enclosure-btn');
            const originalHtml = saveBtn.innerHTML;
            const encId    = inputId.value;
            const newName  = inputName.value.trim();

            if (!newName) {
                inputName.focus();
                return;
            }

            // Loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="ph ph-circle-notch" style="animation:spin 1s linear infinite;"></i> <span>Menyimpan...</span>';

            try {
                // Kirim ke API backend
                const response = await fetch(`/api/enclosures/${encId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ name: newName })
                });

                const result = await response.json();

                if (result.success) {
                    // ─── Update DOM langsung tanpa reload ────────────────
                    // 1. Update h3 pada kartu enclosure yang sesuai
                    if (activeGearBtn) {
                        const card = activeGearBtn.closest('.enclosure-card');
                        if (card) {
                            const h3 = card.querySelector('.enclosure-info h3');
                            if (h3) h3.textContent = newName;
                        }
                        // 2. Update data-name pada tombol gear agar modal berikutnya akurat
                        activeGearBtn.setAttribute('data-name', newName);
                    }

                    // 3. Tutup modal
                    editModal.style.display = 'none';

                    // 4. Toast sukses
                    showToast('Nama kandang berhasil diperbarui!', 'success');
                } else {
                    showToast('Gagal menyimpan: ' + (result.message || 'Unknown error'), 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Gagal terhubung ke server.', 'error');
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalHtml;
            }
        });
    }

    // ─── Toast Notification Helper ───────────────────────────────
    function showToast(message, type = 'success') {
        // Hapus toast lama jika ada
        const old = document.getElementById('enc-toast');
        if (old) old.remove();

        const toast = document.createElement('div');
        toast.id = 'enc-toast';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed; bottom: 2rem; left: 50%; transform: translateX(-50%);
            background: ${type === 'success' ? 'var(--primary-color, #2e7d32)' : '#c62828'};
            color: #fff; padding: 0.75rem 1.5rem; border-radius: 999px;
            font-size: 0.9rem; z-index: 9999; box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            animation: fadeInUp 0.3s ease;
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }
});
</script>
@endpush

