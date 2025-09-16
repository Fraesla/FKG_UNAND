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
                                            <label class="form-label">NIDM/NIP</label>
                                            <input type="text" placeholder="Masukkan NIM" class="form-control" name="nidm" value="{{$dosen->nidm}}" />
                                        </div>
                                        <div>
                                            <label class="form-label">Nama Dosen</label>
                                            <input type="text" placeholder="Masukkan Nama dosen" class="form-control" name="nama" value="{{$dosen->nama}}" />
                                        </div>
                                        <div class="mb-3">
                                          <div class="form-label">Gender</div>
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
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Lahir</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Lahir" id="datepicker-icon-prepend" name="tgl_lahir" value="{{$dosen->tgl_lahir}}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                          <label class="form-label">Alamat</label>
                                          <textarea class="form-control" data-bs-toggle="autosize"  placeholder="Masukkan Alamat..."  style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 56px;" name="alamat">{{$dosen->alamat}}</textarea>
                                        </div>
                                        <div>
                                            <label class="form-label">No.HP</label>
                                            <input type="text" placeholder="Masukkan No.HP" class="form-control" name="no_hp" value="{{$dosen->no_hp}}"/>
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