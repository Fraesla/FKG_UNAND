@extends('admin.layouts.app', [
'activePage' => 'gigi',
'activeDrop' => 'seminarhasil',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Seminar Hasil</h2>
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    Pengeditan Data Seminar Hasil
                                </h3>
                                <a href="/admin/seminarhasil/" class="btn btn-secondary btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icon-tabler-arrow-left">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l14 0" />
                                        <path d="M5 12l6 6" />
                                        <path d="M5 12l6 -6" />
                                    </svg>
                                    Back
                                </a>
                            </div>
                            <div class="card-body">
                                <form action="/admin/seminarhasil/update/{{$seminar_hasil->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Mahasiswa</label>
                                             <select class="form-select" name="id_mahasiswa">
                                                <option>Pilih Data Mahasiswa</option>
                                                @foreach($mahasiswa as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $seminar_hasil->id_mahasiswa == $data->id ? 'selected' : '' }}>
                                                        No.BP : {{$data->nobp}} | Nama Mahasiswa : {{$data->nama}}  
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                       <div>
                                            <label class="form-label">Dosen Pembimbing 1</label>
                                             <select class="form-select" name="dosen_pembimbing_1">
                                                <option>Pilih Data Dosen</option>
                                                @foreach($dosen as $data)
                                                    <option value="{{$data->nama}}"
                                                        {{ $seminar_hasil->dosen_pembimbing_1 == $data->nama ? 'selected' : '' }}>
                                                        NIP : {{$data->nip}} | Nama Dosen : {{$data->nama}}  
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 2</label>
                                             <select class="form-select" name="dosen_pembimbing_2">
                                                <option>Pilih Data Dosen</option>
                                                @foreach($dosen as $data)
                                                    <option value="{{$data->nama}}"
                                                        {{ $seminar_hasil->dosen_pembimbing_2 == $data->nama ? 'selected' : '' }}>
                                                        NIP : {{$data->nip}} | Nama Dosen : {{$data->nama}}  
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                         <div>
                                            <label class="form-label">Penguji 1</label>
                                            <input type="text" placeholder="Masukkan Penguji 1" class="form-control" name="penguji_1" value="{{$seminar_hasil->penguji_1}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 2</label>
                                            <input type="text" placeholder="Masukkan Penguji 2" class="form-control" name="penguji_2" value="{{$seminar_hasil->penguji_2}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 3</label>
                                            <input type="text" placeholder="Masukkan Penguji 3" class="form-control" name="penguji_3" value="{{$seminar_hasil->penguji_3}}"/>
                                        </div>
                                        <div class="mb-3">
                                            <label>Surat Seminar hasil</label>
                                            <input type="file" name="surat_hasil" class="form-control">
                                            @if($seminar_hasil->surat_hasil)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$seminar_hasil->surat_hasil) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label>File Draft</label>
                                            <input type="file" name="file_draft" class="form-control">
                                            @if($seminar_hasil->file_draft)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$seminar_hasil->file_draft) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label>Bukti Izin</label>
                                            <input type="file" name="bukti_izin" class="form-control">
                                            @if($seminar_hasil->bukti_izin)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$seminar_hasil->bukti_izin) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <label>Lembar Jadwal</label>
                                            <input type="file" name="lembar_jadwal" class="form-control">
                                            @if($seminar_hasil->lembar_jadwal)
                                                <small class="text-muted">
                                                    File saat ini: 
                                                    <a href="{{ asset('storage/'.$seminar_hasil->lembar_jadwal) }}" target="_blank">Lihat</a>
                                                </small>
                                            @endif
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