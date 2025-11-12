@extends('mahasiswa.layouts.app', ['activePage' => 'absensi'])

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📅 Kalender Jadwal Absen Mahasiswa</h1>

        {{-- Tombol Riwayat Absen --}}
        <a href="/mahasiswa/absensi/mahasiswa" 
           class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
            <i class="bi bi-clock-history"></i> 
            <span>Riwayat Absen</span>
        </a>
        
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
});
</script>