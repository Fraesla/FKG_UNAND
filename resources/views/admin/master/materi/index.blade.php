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
                  @include('components.alert')
              </div>
              <!-- Page title actions -->
      </div>
   </div>
</div>
<!-- END PAGE HEADER -->
<!-- BEGIN PAGE BODY -->
<div class="page-body">
    <div class="container-xl">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Tabel Materi</h3>
                    <div class="d-flex gap-2">
                        <!-- Tombol Import -->
                            <form action="/admin/materi/import" method="POST" enctype="multipart/form-data" class="d-inline-block me-2">
                                @csrf
                                <label class="btn btn-primary btn-mm mb-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                        class="icon icon-tabler icon-tabler-upload">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 9l5 -5l5 5" />
                                        <path d="M12 4v12" />
                                    </svg>
                                    Import
                                    <input type="file" name="file" class="d-none" onchange="this.form.submit()">
                                </label>
                            </form>

                            <!-- Tombol Export -->
                            <a href="#" class="btn btn-info btn-mm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                    class="icon icon-tabler icon-tabler-download me-1">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                    <path d="M7 11l5 5l5 -5" />
                                    <path d="M12 4l0 12" />
                                </svg>
                                Export
                            </a>
                    </div>
                </div>
                <form action="/admin/materi/feature" method="GET">
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <!-- Show Entries -->
                            <div class="text-secondary">Show
                                <div class="mx-2 d-inline-block">
                                    <select name="entries" class="form-select form-select-mm" onchange="this.form.submit()">
                                        <option value="5" {{ request('entries') == 5 ? 'selected' : '' }}>5</option>
                                        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                                    </select>
                                </div>
                                entries
                            </div>

                            <!-- Search + Button ADD di kanan -->
                            <div class="ms-auto text-secondary d-flex align-items-center">
                                <span class="me-2">Search:</span>
                                <input type="text" class="form-control form-control-mm" 
                                       aria-label="Search data materi" 
                                       name="search" 
                                       placeholder="Cari Data materi ..." 
                                       value="{{ request('search') }}">

                                <!-- <a href="/admin/materi/add" class="btn btn-success btn-mm ms-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                    ADD
                                </a> -->
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <!-- <th class="w-1">
                                    <input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices">
                                </th> -->
                                <th class="w-1">
                                    No.
                                    <!-- Download SVG icon from http://tabler.io/icons/icon/chevron-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon icon-sm icon-thick icon-2">
                                        <path d="M6 15l6 -6l6 6"></path>
                                    </svg>
                                </th>
                                <th>Jadwal</th>
                                <th>Absensi</th>
                                <th>Judul Materi</th>
                                <th>File Materi</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @forelse($materi as $data)
                            <tr>
                               <!--  <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                    type="checkbox" aria-label="Select invoice">
                                </td> -->
                                <td><span class="text-secondary"> {{$no++}}</span></td>
                                <td class="text-secondary">
                                    @if($data->id_jadwal_blok)
                                        {{-- Jadwal Blok --}}
                                        <strong>Data {{ $data->blok_kelas ?? '-' }}</strong><br>
                                        <strong>Minggu ke-{{ $data->blok_minggu ?? '-' }}</strong><br>
                                        Tanggal : {{ \Carbon\Carbon::parse($data->blok_tgl ?? '')->format('d/m/Y') }}<br>
                                        Hari : {{ ucfirst($data->blok_hari) ?? '-' }}<br>
                                        Jam : {{ $data->blok_jam_mulai ?? '-' }} - {{ $data->blok_jam_selesai ?? '-' }}<br>
                                        <span class="text-info">Mata Kuliah : {{ $data->blok_makul ?? '-' }}</span><br>
                                        <span class="text-success">Dosen : {{ $data->blok_dosen ?? '-' }}</span><br>
                                        <span class="text-warning">Ruangan : {{ $data->blok_ruangan ?? '-' }}</span>
                                    @elseif($data->id_jadwal_metopen)
                                        {{-- Jadwal Metopen --}}
                                        <strong>Data Metopen</strong><br>
                                        <strong>Minggu ke-{{ $data->metopen_minggu ?? '-' }}</strong><br>
                                        Tanggal : {{ \Carbon\Carbon::parse($data->metopen_tgl ?? '')->format('d/m/Y') }}<br>
                                        Hari : {{ ucfirst($data->metopen_hari) ?? '-' }}<br>
                                        Jam : {{ $data->metopen_jam_mulai ?? '-' }} - {{ $data->metopen_jam_selesai ?? '-' }}<br>
                                        <span class="text-info">Mata Kuliah : {{ $data->metopen_makul ?? '-' }}</span><br>
                                        <span class="text-success">Dosen : {{ $data->metopen_dosen ?? '-' }}</span><br>
                                        <span class="text-warning">Ruangan : {{ $data->metopen_ruangan ?? '-' }}</span>
                                    @else
                                        <span class="text-danger">Tidak ada jadwal terhubung</span>
                                    @endif
                                </td>
                                {{-- Kolom Absen --}}
                                <td>
                                    @if(!empty($data->id_absen_dosen))
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill">
                                            Sudah Absen
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill">
                                            Belum Mengisi Absen
                                        </span>
                                    @endif
                                </td>

                                {{-- Kolom Judul Materi --}}
                                <td>
                                    @if(!empty($data->judul))
                                        <span class="text-secondary">{{ $data->judul }}</span>
                                    @else
                                        <span class="badge bg-warning text-white px-3 py-2 rounded-pill">
                                            Belum Isi Judul Materi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-secondary">
                                    @if($data->file)
                                        <a href="{{ asset('storage/'.$data->file) }}" target="_blank" class="btn btn-sm btn-primary">
                                            Lihat File Materi
                                        </a>
                                    @else
                                        <span class="text-muted">Belum ada file</span>
                                    @endif
                                </td>
                                <td class="w-0">
                                    <div class="d-flex gap-1">
                                        <!-- Tombol Edit -->
                                        <a href="/admin/materi/edit/{{$data->id}}" class="btn btn-warning btn-sm p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </a>

                                        <!-- Tombol Delete -->
                                        <button type="button" class="btn btn-danger btn-sm p-1" onclick="deleteData({{ $data->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" 
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                                 class="icon icon-tabler icons-tabler-outline icon-tabler-trash-x">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M4 7h16" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                <path d="M10 12l4 4m0 -4l-4 4" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <!-- <td class="text-end">
                                    <span class="dropdown">
                                        <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" style="">
                                            <a class="dropdown-item" href="#">
                                                Action
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                Another action
                                            </a>
                                        </div>
                                    </span>
                                </td> -->
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row g-2 justify-content-center justify-content-sm-between">
                        <div class="col-auto d-flex align-items-center">
                            <p class="m-0 text-secondary">
                                Showing
                                <strong>{{ $materi->firstItem() }}</strong>
                                to
                                <strong>{{ $materi->lastItem() }}</strong>
                                of
                                <strong>{{ $materi->total() }}</strong>
                                entries
                            </p>
                        </div>
                        <div class="col-auto">
                            {{ $materi->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deleteData(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/materi/delete/" + id;
        }
    })
}
</script>
<!-- END PAGE BODY -->
@endsection