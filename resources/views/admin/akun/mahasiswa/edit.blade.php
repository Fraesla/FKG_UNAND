@extends('admin.layouts.app', [
'activePage' => 'akun',
'activeDrop' => 'mahasiswa',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Mahasiswa</h2>
                  @if ($errors->any())
                    <div id="alert-error" class="alert alert-danger alert-dismissible fade show position-relative" role="alert">
                        <strong>⚠️ Terjadi Kesalahan pada Pengisian Formulir:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <div class="progress position-absolute bottom-0 start-0 w-100" style="height: 3px;">
                            <div id="progress-bar-error" class="progress-bar bg-danger" role="progressbar"></div>
                        </div>
                    </div>
                    @endif
              </div>
              <!-- Page title actions -->
      </div>
   </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards row-cols-1 row-cols-md-12">
            <div class="col">
                <div class="row row-cards">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Pengeditan Data Mahasiswa
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/mahasiswa/update/{{$mahasiswa->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">NO.BP</label>
                                            <input type="text" placeholder="Masukkan NO.BP" class="form-control" name="nobp" value="{{$mahasiswa->nobp}}" />
                                            @error('nobp')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Lengakap Mahasiswa</label>
                                            <input type="text" placeholder="Masukkan Nama Lengkap Mahasiswa" class="form-control" name="nama" value="{{$mahasiswa->nama}}" />
                                            @error('nama')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Jenis Kelamin</div>
                                          <div>
                                            <label class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="gender" value="Laki-Laki" {{ $mahasiswa->gender == 'Laki-Laki' ? 'checked' : '' }}>
                                              <span class="form-check-label">Laki-Laki</span>
                                            </label>
                                            <label class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="gender" value="Perempuan" {{ $mahasiswa->gender == 'Perempuan' ? 'checked' : '' }}>
                                              <span class="form-check-label">Perempuan</span>
                                            </label>
                                          </div>
                                          @error('gender')
                                            <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                          @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Level UKT</label>
                                            <input type="text" placeholder="Masukkan Level UKT" class="form-control" name="ukt" value="{{$mahasiswa->ukt}}" />
                                            @error('ukt')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Tahun Ajaran</label>
                                            <select class="form-select" name="id_tahun_ajaran">
                                                <option>
                                                    Pilih Tahun Ajaran
                                                </option>
                                                @foreach($tahun as $data)
                                                <option value="{{$data->id}}" {{ $mahasiswa->id_tahun_ajaran == $data->id ? 'selected' : '' }}>
                                                    Tahun Ajaran : {{$data->nama}} | Semester : {{$data->semester}} | Status : 
                                                    <?php if ($data->status=="1"): ?>
                                                        Aktif
                                                    <?php else: ?>
                                                        Noaktif
                                                    <?php endif ?>
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('id_tahun_ajaran')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Foto</div>
                                            {{-- tampilkan foto lama kalau ada --}}
                                            @if($mahasiswa->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$mahasiswa->foto) }}" alt="Foto Mahasiswa" width="120" class="img-thumbnail">
                                                </div>
                                            @endif
                                            {{-- input upload foto baru --}}
                                            <input type="file" class="form-control" accept="image/*" name="foto">
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-4 w-100">
                                                Simpan
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/arrow-right -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="icon icon-right icon-2">
                                                    <path d="M5 12l14 0" />
                                                    <path d="M13 18l6 -6" />
                                                    <path d="M13 6l6 6" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE BODY -->
@endsection