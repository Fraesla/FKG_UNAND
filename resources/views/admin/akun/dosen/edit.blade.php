@extends('admin.layouts.app', [
'activePage' => 'akun',
'activeDrop' => 'dosen',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Dosen</h2>
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
                                    Pengeditan Data Dosen
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/dosen/update/{{$dosen->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Nama Dosen</label>
                                            <input type="text" placeholder="Masukkan Nama Dosen" class="form-control" name="nama" value="{{$dosen->nama}}"/>
                                            @error('nama')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">NIP</label>
                                            <input type="text" placeholder="Masukkan NIP" class="form-control" name="nip" value="{{$dosen->nip}}"/>
                                            @error('nip')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">NIDN</label>
                                            <input type="text" placeholder="Masukkan NIDN" class="form-control" name="nidn" value="{{$dosen->nidn}}"/>
                                            @error('nidm')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Jenis Kelamin</div>
                                          <div>
                                            <label class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="gender" value="Laki-Laki" {{ $dosen->gender == 'Laki-Laki' ? 'checked' : '' }}>
                                              <span class="form-check-label">Laki-Laki</span>
                                            </label>
                                            <label class="form-check form-check-inline">
                                              <input class="form-check-input" type="radio" name="gender" value="Perempuan" {{ $dosen->gender == 'Perempuan' ? 'checked' : '' }}>
                                              <span class="form-check-label">Perempuan</span>
                                            </label>
                                          </div>
                                          @error('gender')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                          @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Pangkat / Golongan</label>
                                            <input type="text" placeholder="Masukkan Pangkat / Golongan" class="form-control" name="pangol" value="{{$dosen->pangol}}"/>
                                            @error('pangol')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Naik Pangkat Terakhir</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Naik Pangkat Terakhir" id="datepicker-icon-prepend" name="napater" value="{{$dosen->napater}}">
                                            </div>
                                            @error('napater')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Naik Pangkat Berikutnya</label>
                                            <input type="text" placeholder="Masukkan Naik Pangkat Berikutnya" class="form-control" name="napaber" value="{{$dosen->napaber}}"/>
                                            @error('napaber')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">JF</label>
                                            <input type="text" placeholder="Masukkan JF" class="form-control" name="jf" value="{{$dosen->jf}}"/>
                                            @error('jf')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">JS</label>
                                            <input type="text" placeholder="Masukkan JS" class="form-control" name="js" value="{{$dosen->js}}"/>
                                            @error('js')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Naik Jabatan Terakhir</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Naik Jabatan Terakhir" id="datepicker-icon-prepend_2" name="najater" value="{{$dosen->najater}}">
                                            </div>
                                            @error('najater')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Pendidikan Terakhir</label>
                                            <input type="text" placeholder="Masukkan Pendidikan Terakhir" class="form-control" name="penter" value="{{$dosen->penter}}"/>
                                            @error('penter')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="form-label">Keterangan</label>
                                            <input type="text" placeholder="Masukkan Keterangan" class="form-control" name="keterangan" value="{{$dosen->keterangan}}"/>
                                            @error('keterangan')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Foto</div>
                                            {{-- tampilkan foto lama kalau ada --}}
                                            @if($dosen->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/'.$dosen->foto) }}" alt="Foto dosen" width="120" class="img-thumbnail">
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