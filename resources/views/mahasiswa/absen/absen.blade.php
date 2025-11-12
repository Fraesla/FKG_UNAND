@extends('mahasiswa.layouts.app', ['activePage' => 'absensi'])

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 mx-auto" style="max-width: 500px;">
        <div class="card-body text-center">
            {{-- Tombol Back --}}
            <div class="text-start mb-3">
                <a href="/mahasiswa/absensi" class="btn btn-outline-light btn-sm d-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg> 
                    <span>BACK</span>
                </a>
            </div>
            <h3 class="mb-3">🧾 Isi Absensi</h3>
            <div class="text-center mb-3">
                <i class="fas fa-user-graduate text-info"></i>
                Mahasiswa: <strong>{{ $mahasiswa->nama ?? 'Tidak diketahui' }}</strong>
            </div>
            <p class="text-muted">
                Dosen: <strong>{{ $absen->nama_dosen }}</strong>
            </p>
            <p>📘 Kode Kuliah: {{ $absen->kode_makul }}</p>
            <p>📘 Mata Kuliah: {{ $absen->nama_makul }}</p>
            <p class="text-muted">
                Tanggal: <strong>{{ $absen->tgl }}</strong>
            </p>
            <p>📘 Hari: {{ $absen->hari }}</p>
            <div class="text-muted">
                Jam Masuk: <strong>{{ $absen->jam_masuk ?? '-' }}</strong>
            </div>
            <div class="text-muted">
                Jam Pulang: <strong>{{ $absen->jam_pulang ?? '-' }}</strong>
            </div>
            <p>📘 Ruangan: {{ $absen->ruangan }}</p>

            @if($absen->judul_materi)
                <div class="mt-2">
                    <i class="fas fa-book-open text-info"></i>
                        Materi: <strong>{{ $absen->judul_materi }}</strong><br>
                </div>
            @endif

            <hr>

            <form method="POST" action="/mahasiswa/absensi/absen">
                @csrf
                <input type="hidden" name="tgl" value="{{ $absen->tgl }}">
                <input type="hidden" name="jam_masuk" value="{{ $absen->jam_masuk }}">
                <input type="hidden" name="jam_pulang" value="{{ $absen->jam_pulang }}">
                <input type="hidden" name="id_mahasiswa" value="{{ $mahasiswa->id }}">
                <input type="hidden" name="id_jadwal_mahasiswa" value="{{ $absen->id_jadwal_dosen }}">
                <input type="hidden" name="id_absen_dosen" value="{{ $absen->id }}">
                <input type="hidden" name="keterangan">

                <div class="mb-3">
                    <label for="status" class="form-label">Status Kehadiran</label>
                    <select name="status" id="status" class="form-select">
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alfa">Alfa</option>
                    </select>
                </div>



                <!-- <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="2" placeholder="Isi keterangan jika status kehadiran nya dipilih izin atau sakit "></textarea>
                </div> -->


                <button type="submit" class="btn btn-primary w-100">Isi Absensi</button>
            </form>
        </div>
    </div>
</div>
@endsection