@extends('mahasiswa.layouts.app', ['activePage' => 'absensi'])

@section('content')
<style>
    .profile-card {
        background: #111827;
        border-radius: 14px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        transition: 0.2s ease;
        border: 1px solid #1f2937;
        position: relative;
        z-index: 99999;
    }

    .profile-card:hover {
        transform: translateY(-2px);
    }

    .profile-img {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4b5563;
    }

    .profile-name {
        font-size: 15px;
        font-weight: 600;
        color: #fff;
    }

    .profile-sub {
        font-size: 12px;
        color: #9ca3af;
    }

    /* Dropdown custom */
    .menu-dropdown {
        position: absolute;
        top: 60px;
        right: 10px;
        width: 180px;

        background: #1f2937;
        border: 1px solid #374151;
        border-radius: 8px;
        padding: 6px 0;

        display: none; /* HIDDEN BY DEFAULT */
        opacity: 0;
        transform: translateY(-5px);
        transition: 0.2s ease;
        z-index: 999999;
    }

    .menu-dropdown.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .dropdown-item-btn {
        display: block;
        width: 100%;
        padding: 8px 14px;
        color: #e5e7eb;
        text-align: left;
        font-size: 14px;
        text-decoration: none;
    }

    .dropdown-item-btn:hover {
        background: #374151;
        color: white;
    }

</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3" style="z-index:999999; position: relative;">
        <h1>📅 Kalender Jadwal Absen Mahasiswa</h1>

        {{-- AREA KANAN: Riwayat Absen + Profile Card --}}
        <div class="d-flex align-items-center gap-3 ms-auto">

            {{-- Tombol Riwayat Absen --}}
            <a href="/mahasiswa/absensi/mahasiswa" 
               class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-clock-history"></i> 
                <span>Riwayat Absen</span>
            </a>

            <!-- ⭐ PROFILE CARD -->
            <div class="profile-card">

                <img src="{{ $mahasiswa->foto ? asset('storage/'.$mahasiswa->foto) : asset('assets/images/default-fkg.jpg') }}"
                     width="50" height="50" class="rounded-circle object-cover profile-img">

                <div>
                    <div class="profile-name">Nama Mahasiswa : {{ $mahasiswa->nama }}</div>
                    <div class="profile-sub">NIP : {{ $mahasiswa->nobp }}</div>
                </div>

                <!-- CUSTOM MENU BUTTON -->
                <button id="toggleMenu" class="btn btn-sm" 
                        style="background: #1f2937; border:1px solid #374151; color:white;">
                    ☰
                </button>

                <!-- CUSTOM MENU -->
                <div id="menuDropdown" class="menu-dropdown">
                    <a href="/mahasiswa/profile" class="dropdown-item-btn">Edit Profile</a>
                    <a href="/mahasiswa/changepass" class="dropdown-item-btn">Ubah Password</a>
                </div>

            </div>

        </div>
        
    </div>

    <div id="calendar" style="min-height: 80vh;"></div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: @json($events),

        eventClick: function(info) {
            const data = info.event.extendedProps;

            // Generate QR Code
            let qrCanvas = document.createElement('canvas');
            new QRious({
                element: qrCanvas,
                value: `{{ url('/mahasiswa/absensi/isi') }}/${data.id}`,
                size: 180
            });

            // Template HTML untuk popup
            const htmlContent = `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;font-size:14px;">
                    <div style="background:#f8f9fa;border-radius:12px;padding:10px 15px;width:100%;box-shadow:0 1px 3px rgba(0,0,0,0.1)">
                        <h3 style="margin:0;font-size:16px;color:#333;text-align:center;">${info.event.title}</h3>
                        <p style="margin:0;text-align:center;color:#555;">
                            <strong>Tanggal:</strong> ${data.tgl}<br>
                            <strong>Jam Masuk:</strong> ${data.jam_masuk ?? '-'}<br>
                            <strong>Jam Pulang:</strong> ${data.jam_pulang ?? '-'}<br>
                        </p>
                    </div>
                    <div style="background:white;padding:10px;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.2)">
                        <h4 style="margin-bottom:5px;text-align:center;">QR Code</h4>
                        <div id="qrContainer" style="display:flex;justify-content:center;"></div>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Detail Absensi Jadwal Mahasiswa',
                html: htmlContent,
                width: 380,
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                background: '#f0f2f5',
                didOpen: () => {
                    Swal.getHtmlContainer().querySelector('#qrContainer').appendChild(qrCanvas);
                }
            });
        }
    });

    calendar.render();

    // CUSTOM MENU HANDLER
    const toggleBtn = document.getElementById("toggleMenu");
    const dropdown = document.getElementById("menuDropdown");

    toggleBtn.addEventListener("click", () => {
        dropdown.classList.toggle("show");
    });

    // Close dropdown if click outside
    document.addEventListener("click", (e) => {
        if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });
});
</script>