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
            @endforeach


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
            <form action="{{ route('enclosure.select.post')}}" method="POST" id="edit-enclosure-form">
                @csrf
                <input type="hidden" name="enclosure_id" id="edit-enclosure-id" value="{{ $enclosure->id }}">
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

    // ==============================
    // Theme Toggle
    // ==============================
    const themeToggleBtn = document.getElementById('enclosure-theme-toggle');
    const themeIcon = themeToggleBtn?.querySelector('i');

    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);

        if (themeIcon) {
            themeIcon.className = theme === 'dark'
                ? 'ph ph-sun'
                : 'ph ph-moon';
        }
    }

    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);

    themeToggleBtn?.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');

        setTheme(current === 'dark' ? 'light' : 'dark');
    });



    // ==============================
    // Modal Edit Enclosure
    // ==============================
    const editBtns = document.querySelectorAll('.edit-enclosure-btn');
    const editModal = document.getElementById('edit-enclosure-modal');
    const closeEditBtn = document.getElementById('close-edit-modal');

    const inputName = document.getElementById('edit-enclosure-name');
    const inputId = document.getElementById('edit-enclosure-id');

    editBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            const id = btn.dataset.id;
            const name = btn.dataset.name;

            inputId.value = id;
            inputName.value = name;

            editModal.style.display = 'flex';
        });
    });

    closeEditBtn?.addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    editModal?.addEventListener('click', (e) => {
        if (e.target === editModal) {
            editModal.style.display = 'none';
        }
    });

});
</script>
@endpush

