@extends('dosen.layouts.app', ['activePage' => 'jadwal'])

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h2 class="mb-0">📝 Isi Absen Dosen</h2>
            <a href="{{ url('/dosen/absendosen') }}" class="btn btn-light btn-sm">
                ← Kembali ke Kalender
            </a>
        </div>

        <div class="card-body">
            <h3 class="text-muted mb-3">Informasi Jadwal Absen</h3>

            <table class="table table-bordered table-sm align-middle">
                <tr>
                    <th style="width: 25%">Nama Dosen</th>
                    <td>{{ $absen->nama_dosen }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ \Carbon\Carbon::parse($absen->tgl)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Jam Masuk (Terjadwal)</th>
                    <td>{{ $absen->jam_masuk ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jam Pulang (Terjadwal)</th>
                    <td>{{ $absen->jam_pulang ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status Sekarang</th>
                    <td>
                        <div class="d-flex align-items-center justify-content-start" style="gap: 0.75rem;">
                            {{-- STATUS BADGE --}}
                            <span class="badge 
                                @if($absen->status == 'hadir') bg-success 
                                @elseif($absen->status == 'izin') bg-warning text-dark 
                                @elseif($absen->status == 'alfa') bg-danger 
                                @else bg-secondary text-light 
                                @endif">
                                {{ ucfirst($absen->status) ?? 'Belum Absen' }}
                            </span>

                            {{-- Tombol isi absen atau QR --}}
                            @if($absen->status !== 'hadir')
                                <form id="formAbsen" 
                                      action="{{ url('/dosen/absendosen/absen/' . $absen->id) }}" 
                                      method="POST" class="m-0">
                                    @csrf
                                    <button type="button" 
                                            class="btn btn-success btn-sm d-flex align-items-center gap-1"
                                            id="btnIsiAbsen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="icon icon-tabler icon-tabler-clipboard-text">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                            <path d="M9 12h6" /><path d="M9 16h6" />
                                        </svg>
                                        Isi Absen
                                    </button>
                                </form>
                            @else
                                <button type="button"
                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
                                    id="btnQrCode"
                                    data-qrcode="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ $absen->qr }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h3v3h3v3h-6z"/>
                                    </svg>
                                    QR Code
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $absen->keterangan ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection


<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tombol Isi Absen
    const btnIsi = document.getElementById('btnIsiAbsen');
    if (btnIsi) {
        btnIsi.addEventListener('click', function () {
            Swal.fire({
                title: 'Konfirmasi Absen',
                text: "Apakah Anda yakin ingin mengisi absen sekarang?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Isi Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formAbsen').submit();
                }
            });
        });
    }

    // Tombol Lihat QR Code
    const btnQr = document.getElementById('btnQrCode');
    if (btnQr) {
        btnQr.addEventListener('click', function (e) {
            e.preventDefault();
            const imgUrl = this.getAttribute('data-qrcode');
            Swal.fire({
                title: 'QR Code Absen',
                html: `
                    <img src="${imgUrl}" alt="QR Code" 
                         style="width: 250px; height: 250px; border-radius: 10px; border: 2px solid #ddd;">
                    <p class="mt-2 text-muted">Tunjukkan QR Code ini untuk verifikasi.</p>
                `,
                showConfirmButton: true,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#0d6efd'
            });
        });
    }
});
</script>

{{-- Alert sukses setelah submit --}}
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: "{{ session('success') }}",
    confirmButtonColor: '#198754',
});
</script>
@endif