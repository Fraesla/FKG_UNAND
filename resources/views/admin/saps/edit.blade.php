@extends('admin.layouts.app', [
'activePage' => 'saps',
'activeDrop' => '',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data SAPS</h2>
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
                                    Pengeditan Data SAPS
                                </h3>
                            </div>
                            <div class="card-body">
                                <form action="/admin/saps/update/{{$saps->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Mahasiswa</label>
                                             <select class="form-select" name="id_mahasiswa">
                                                <option>Pilih Data Mahasiswa</option>
                                                @foreach($mahasiswa as $data)
                                                    <option value="{{$data->id}}"
                                                        {{ $saps->id_mahasiswa == $data->id ? 'selected' : '' }}>
                                                        No.BP : {{$data->nim}} | Nama Mahasiswa : {{$data->nama}}  
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Jumlah Point A</label>
                                            <input type="text" placeholder="Masukkan Jumlah Point A" class="form-control" name="jml_point_a" value="{{$saps->jml_point_a}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Jumlah Point B</label>
                                            <input type="text" placeholder="Masukkan Jumlah Point B" class="form-control" name="jml_point_b" value="{{$saps->jml_point_b}}"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Jumlah Point C</label>
                                            <input type="text" placeholder="Masukkan Jumlah Point C" class="form-control" name="jml_point_c" value="{{$saps->jml_point_c}}"/>
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