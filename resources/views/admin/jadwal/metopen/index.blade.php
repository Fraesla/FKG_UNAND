@extends('admin.layouts.app', [
'activePage' => 'jadwal',
'activeDrop' => 'jadmetopen',
])
@section('content')
<!-- BEGIN PAGE HEADER -->
<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <!-- Page pre-title -->
               <div class="page-pretitle">Aplikasi FKG</div>
                  <h2 class="page-title">Data Jadwal Mata Kuliah (Data Metopen)</h2>
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
                    <h3 class="card-title">Tabel Jadwal Mata Kuliah (Data Metopen)</h3>
                    <!-- <div class="d-flex align-items-center ms-auto">
                        <label class="form-label me-2 mb-0">Blok</label>
                        <select class="form-select"  name="id_kelas" style="min-width: 200px;" onchange="window.location.href='{{ route('admin.jadwal.makul.read') }}?id_kelas='+this.value">
                            <option value="">-- Pilih Blok --</option>
                            @foreach($blok as $data)
                                <option value="{{ $data->id }}" {{ request('id_kelas') == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->
                </div>
                <form action="/admin/jadmetopen/feature" method="GET">
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
                                       aria-label="Search data Jadwal Mata Kuliah (Data Metopen)" 
                                       name="search" 
                                       placeholder="Cari Data Jadwal Mata Kuliah (Data Metopen) ..." 
                                       value="{{ request('search') }}">

                                <a href="/admin/jadmetopen/add" class="btn btn-success btn-mm ms-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" 
                                         class="icon icon-tabler icons-tabler-outline icon-tabler-plus">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5v14" />
                                        <path d="M5 12h14" />
                                    </svg>
                                    ADD
                                </a>
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
                                <th>Minggu Ke</th>
                                <th>Hari</th>
                                <th class="text-center">Jam</th>
                                <th>Mata Kuliah</th>
                                <th>Ruangan</th>
                                <th class="text-center" colspan="2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @forelse($jadmetopen as $data)
                            <tr>
                               <!--  <td>
                                    <input class="form-check-input m-0 align-middle table-selectable-check"
                                    type="checkbox" aria-label="Select invoice">
                                </td> -->
                                <td><span class="text-secondary"> {{$no++}}</span></td>
                                <td>Minggu Ke-{{ $data->minggu }}</td>
                                <td>{{ $data->hari }}</td>
                                <td class="text-center">{{ $data->jam_mulai }} - {{ $data->jam_selesai }}</td>
                                <td>{{ $data->makul }}</td>
                                <td>{{ $data->ruangan }}</td>
                                <td class="w-0">
                                    <div class="d-flex gap-1">
                                        @switch($data->makul)
                                            @case('Kuliah Pengantar')
                                            @case('Pleno')
                                                {{-- Hanya tampilkan Absensi --}}
                                                <a href="/admin/makul" class="btn btn-success btn-sm p-1">
                                                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  
                                                         viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  
                                                         class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-text">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                        <path d="M9 12h6" /><path d="M9 16h6" />
                                                    </svg>
                                                </a>

                                                <!-- Edit -->
                                                <a href="/admin/jadmetopen/edit/{{$data->id}}" class="btn btn-warning btn-sm p-1">
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

                                                <!-- Delete -->
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
                                                @break

                                            @default
                                                {{-- Default: semua tombol --}}
                                                <!-- Nilai -->
                                                <a href="/admin/nilai" class="btn btn-primary btn-sm p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"  
                                                         class="icon icon-tabler icons-tabler-outline icon-tabler-browser-check">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M4 4m0 1a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-14a1 1 0 0 1 -1 -1z" />
                                                        <path d="M4 8h16" /><path d="M8 4v4" />
                                                        <path d="M9.5 14.5l1.5 1.5l3 -3" />
                                                    </svg>
                                                </a>

                                                <!-- Absensi -->
                                                <a href="/admin/makul" class="btn btn-success btn-sm p-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                         class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-text">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                                        <path d="M9 12h6" /><path d="M9 16h6" />
                                                    </svg>
                                                </a>

                                                <!-- Edit -->
                                                <a href="/admin/jadmetopen/edit/{{$data->id}}" class="btn btn-warning btn-sm p-1">
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

                                                <!-- Delete -->
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
                                        @endswitch
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
                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
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
                                <strong>{{ $jadmetopen->firstItem() }}</strong>
                                to
                                <strong>{{ $jadmetopen->lastItem() }}</strong>
                                of
                                <strong>{{ $jadmetopen->total() }}</strong>
                                entries
                            </p>
                        </div>
                        <div class="col-auto">
                            {{ $jadmetopen->links('pagination::bootstrap-5') }}
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
            window.location.href = "/admin/jadmetopen/delete/" + id;
        }
    })
}
</script>
<!-- END PAGE BODY -->
@endsection