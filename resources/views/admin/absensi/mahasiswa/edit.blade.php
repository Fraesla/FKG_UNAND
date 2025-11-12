@extends('admin.layouts.app', [
    'activePage' => 'absensi',
    'activeDrop' => 'absmahasiswa',
])

@section('content')
<div class="page-header d-print-none" aria-label="Page header">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Aplikasi FKG</div>
                <h2 class="page-title">Edit Status Absensi Mahasiswa</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">🧾 Edit Absensi</h3>
                        <a href="{{ url('/admin/absmahasiswa') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Back
                        </a>
                    </div>

                    <div class="card-body bg-dark text-light rounded-bottom">
                        <div class="text-center mb-4">
                            <h4 class="text-info mb-2">Detail Absensi Mahasiswa</h4>
                            <p class="text-muted mb-0">
                                <i class="fas fa-user-graduate text-info"></i>
                                {{ $absmahasiswa->nama_mahasiswa ?? 'Tidak diketahui' }}
                            </p>
                            <p class="text-muted mb-0">Dosen: <strong>{{ $absmahasiswa->nama_dosen ?? '-' }}</strong></p>
                        </div>

                        <div class="mb-3">
                            <p>📘 <strong>Kode Kuliah:</strong> {{ $absmahasiswa->kode_makul ?? '-' }}</p>
                            <p>📘 <strong>Mata Kuliah:</strong> {{ $absmahasiswa->nama_makul ?? '-' }}</p>
                            <p>📅 <strong>Tanggal:</strong> {{ $absmahasiswa->tgl }}</p>
                            <p>🕗 <strong>Jam Masuk:</strong> {{ $absmahasiswa->jam_masuk ?? '-' }}</p>
                            <p>🕙 <strong>Jam Pulang:</strong> {{ $absmahasiswa->jam_pulang ?? '-' }}</p>
                            <p>🏫 <strong>Ruangan:</strong> {{ $absmahasiswa->ruangan ?? '-' }}</p>
                            @if(!empty($absmahasiswa->judul_materi))
                                <p>📖 <strong>Materi:</strong> {{ $absmahasiswa->judul_materi }}</p>
                            @endif
                        </div>

                        <hr class="border-secondary">

                        <form method="POST" action="/admin/absmahasiswa/update/{{$absmahasiswa->id}}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3 text-start">
                                <label for="status" class="form-label text-light">Status Kehadiran</label>
                                <select name="status" id="status" class="form-select bg-dark text-light border-secondary">
                                    <option value="hadir" {{ $absmahasiswa->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                    <option value="izin" {{ $absmahasiswa->status == 'izin' ? 'selected' : '' }}>Izin</option>
                                    <option value="sakit" {{ $absmahasiswa->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                    <option value="alfa" {{ $absmahasiswa->status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                </select>
                            </div>

                            <div class="mb-3 text-start">
                                <label for="keterangan" class="form-label text-light">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" 
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Masukkan keterangan jika izin atau sakit...">{{ $absmahasiswa->keterangan }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div> {{-- end card body --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection