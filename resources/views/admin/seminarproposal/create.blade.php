@extends('admin.layouts.app', [
'activePage' => 'seminarproposal',
'activeDrop' => '',
])
@section('content')

<div class="page-header d-print-none" aria-label="Page header">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle">Aplikasi FKG</div>
            <h2 class="page-title">Data Seminar Proposal</h2>
         </div>
      </div>
   </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Penambahan Data Seminar Proposal</h3>
                    </div>
                    <div class="card-body">
                        <form id="seminarForm" action="{{ url('/admin/seminarproposal/create') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                            <!-- Data Mahasiswa -->
                            <div class="space-y mb-3">
                                <div>
                                    <label class="form-label">Mahasiswa</label>
                                    <select class="form-select" name="id_mahasiswa">
                                        <option>Pilih Data Mahasiswa</option>
                                        @foreach($mahasiswa as $data)
                                            <option value="{{$data->id}}">
                                                No.BP: {{$data->nim}} | Nama Mahasiswa: {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Dosen Pembimbing 1</label>
                                    <select class="form-select" name="dosen_pembimbing_1">
                                        <option>Pilih Data Dosen</option>
                                        @foreach($dosen as $data)
                                            <option value="{{$data->nama}}">
                                                NIDM: {{$data->nidm}} | Nama Dosen : {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Dosen Pembimbing 2</label>
                                    <select class="form-select" name="dosen_pembimbing_2">
                                        <option>Pilih Data Dosen</option>
                                        @foreach($dosen as $data)
                                            <option value="{{$data->nama}}">
                                                NIDM: {{$data->nidm}} | Nama Dosen : {{$data->nama}} 
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="text" name="penguji_1" class="form-control mb-2" placeholder="Penguji 1">
                                <input type="text" name="penguji_2" class="form-control mb-2" placeholder="Penguji 2">
                                <input type="text" name="penguji_3" class="form-control mb-2" placeholder="Penguji 3">
                            </div>

                            <!-- Upload file tunggal tapi multi -->
                            <div class="mb-3">
                                <label class="form-label">Upload File</label>
                                <input type="file" class="form-control" id="fileInput" multiple accept=".pdf,.doc,.docx,.jpg,.png">
                                <small class="text-muted">Pilih file, lalu klik tombol sesuai field tabel.</small>
                            </div>

                            <!-- Preview -->
                            <div id="filePreview" class="mt-3"></div>

                            <!-- Hidden inputs untuk mapping -->
                            <div id="hiddenInputs"></div>

                            <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Semua</button>
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
        ['surat_proposal','file_draft','bukti_izin','lembar_jadwal'].forEach(field => {
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