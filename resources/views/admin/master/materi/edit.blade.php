@extends('admin.layouts.app', [
'activePage' => 'master',
'activeDrop' => 'materi',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Materi</h2>
                  {{-- Flash Message Error (Validasi) --}}
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">
                                    Pengeditan Data Materi
                                </h3>
                                <a href="/admin/materi/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/materi/update/{{$materi->id}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
                                        <div>
                                            <label class="form-label">Judul Materi</label>
                                            <input type="text" placeholder="Masukkan Judul Materi" class="form-control" name="judul" value="{{$materi->judul}}" />
                                            @error('judul')
                                                <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">File Materi</label>

                                            {{-- Jika ada file lama --}}
                                            @if(!empty($materi->file))
                                                <div class="mb-2">
                                                    @php
                                                        $filePath = 'storage/' . $materi->file;
                                                        $fileExt  = pathinfo($materi->file, PATHINFO_EXTENSION);
                                                        $isImage  = in_array(strtolower($fileExt), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    @endphp

                                                    @if ($isImage)
                                                        {{-- Preview gambar --}}
                                                        <img src="{{ asset($filePath) }}" alt="File Materi" width="150" class="img-thumbnail mb-2">
                                                    @else
                                                        {{-- Preview non-gambar --}}
                                                        <div class="p-2 border rounded bg-secondary d-flex align-items-center justify-content-between">
                                                            <div>
                                                                <i class="bi bi-file-earmark-text me-2 "></i> 
                                                                <strong class="text-light">{{ basename($materi->file) }}</strong>
                                                            </div>
                                                            <a href="{{ asset($filePath) }}" target="_blank" class="btn btn-sm btn-primary">
                                                                Lihat / Download
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            {{-- Input upload file baru --}}
                                            <input type="file" class="form-control" name="file" accept="image/*,.pdf,.doc,.docx,.ppt,.pptx,.zip,.rar">
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti file.</small>
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