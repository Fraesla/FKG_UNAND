@extends('admin.layouts.app', [
'activePage' => 'gigi',
'activeDrop' => 'pengajuan',
])
@section('content')

<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle">Aplikasi FKG</div>
            <h2 class="page-title">Data Pengajuan & Penguji</h2>
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
      </div>
   </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Penambahan Data Pengajuan & Penguji</h3>
                        <a href="/admin/pengajuan/" class="btn btn-secondary btn-sm">
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
                        <form action="{{ url('/admin/pengajuan/create') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="space-y">
                                <div>
                                    <label class="form-label">Mahasiswa</label>
                                    <select class="form-select" name="id_mahasiswa">
                                        <option>Pilih Data Mahasiswa</option>
                                        @foreach($mahasiswa as $data)
                                            <option value="{{$data->id}}">
                                                No.BP: {{$data->nobp}} | Nama Mahasiswa: {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_mahasiswa')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                </div>
                                <div>
                                    <label class="form-label">Dosen Pembimbing 1</label>
                                    <select class="form-select" name="dosen_pembimbing_1">
                                        <option>Pilih Data Dosen</option>
                                        @foreach($dosen as $data)
                                            <option value="{{$data->nama}}">
                                                NIDM: {{$data->nip}} | Nama Dosen : {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dosen_pembimbing_1')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                </div>
                                <div>
                                    <label class="form-label">Dosen Pembimbing 2</label>
                                    <select class="form-select" name="dosen_pembimbing_2">
                                        <option>Pilih Data Dosen</option>
                                        @foreach($dosen as $data)
                                            <option value="{{$data->nama}}">
                                                NIDM: {{$data->nip}} | Nama Dosen : {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dosen_pembimbing_2')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                </div>
                                <div>
                                    <label class="form-label">Judul</label>
                                    <input type="text" class="form-control" name="judul" placeholder="Masukkan Judul"/>
                                    @error('judul')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                </div>

                                <!-- Upload file tunggal tapi multi -->
                                <div class="mb-3">
                                    <label class="form-label">Upload File</label>
                                    <input type="file" class="form-control" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                                    <small class="text-muted">Pilih file, lalu klik tombol sesuai field tabel.</small>
                                    @error('surat_pengajuan')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                     @error('krs')
                                        <div class="text-danger small mt-1">⚠️ {{ $message }}</div>
                                     @enderror
                                </div>

                                <!-- Preview -->
                                <div id="filePreview" class="mt-3"></div>

                                <!-- Hidden inputs untuk mapping -->
                                <div id="hiddenInputs"></div>

                                <div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        Simpan Semua Data
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

<script>
document.getElementById('fileInput').addEventListener('change', function(e) {
    let preview = document.getElementById('filePreview');
    preview.innerHTML = "";

    Array.from(e.target.files).forEach((file, index) => {
        let wrapper = document.createElement('div');
        wrapper.classList.add('mb-3','p-2','border','rounded');

        let fileName = document.createElement('p');
        fileName.innerHTML = `<strong>${file.name}</strong>`;
        wrapper.appendChild(fileName);

        if(file.type.startsWith("image/")){
            let img = document.createElement('img');
            img.classList.add('img-thumbnail','mt-2');
            img.style.maxWidth = "150px";
            img.src = URL.createObjectURL(file);
            wrapper.appendChild(img);
        }

        // Tombol mapping ke field
        let btnGroup = document.createElement('div');
        btnGroup.classList.add('mt-2');
        ['surat_pengajuan','krs'].forEach(field => {
            let btn = document.createElement('button');
            btn.type = "button";
            btn.classList.add('btn','btn-sm','btn-outline-primary','me-2','mt-1');
            btn.innerText = "Simpan ke " + field.replace('_',' ');
            btn.onclick = () => assignFileToField(file, field, index, wrapper);
            btnGroup.appendChild(btn);
        });
        wrapper.appendChild(btnGroup);

        preview.appendChild(wrapper);
    });
});

function assignFileToField(file, field, index, wrapper){
    // bikin input hidden file
    let input = document.createElement('input');
    input.type = "file";
    input.name = field;
    input.files = createFileList(file); // custom FileList
    input.hidden = true;

    // hapus input lama kalau ada
    let oldInput = document.querySelector(`input[name="${field}"]`);
    if(oldInput) oldInput.remove();

    document.getElementById('hiddenInputs').appendChild(input);

    // tandai sukses
    wrapper.style.border = "2px solid green";
    wrapper.querySelectorAll("button").forEach(b=>b.disabled=true);
}

// Helper bikin FileList baru
function createFileList(file) {
    let dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    return dataTransfer.files;
}
</script>
@endsection