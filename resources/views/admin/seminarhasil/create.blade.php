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
                                    Penambahan Data Seminar Hasil
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
                                <form action="/admin/seminarhasil/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
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
                                        </div>
                                        <div>
                                            <label class="form-label">Dosen Pembimbing 1</label>
                                            <select class="form-select" name="dosen_pembimbing_1">
                                                <option>Pilih Data Dosen</option>
                                                @foreach($dosen as $data)
                                                    <option value="{{$data->nama}}">
                                                        NIP: {{$data->nip}} | Nama Dosen : {{$data->nama}} 
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
                                                        NIP: {{$data->nip}} | Nama Dosen : {{$data->nama}} 
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                         <div>
                                            <label class="form-label">Penguji 1</label>
                                            <input type="text" placeholder="Masukkan Penguji 1" class="form-control" name="penguji_1" />
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 2</label>
                                            <input type="text" placeholder="Masukkan Penguji 2" class="form-control" name="penguji_2" />
                                        </div>
                                        <div>
                                            <label class="form-label">Penguji 3</label>
                                            <input type="text" placeholder="Masukkan Penguji 3" class="form-control" name="penguji_3" />
                                        </div>
                                        <!-- <div>
                                            <label class="form-label">Surat Seminar Hasil</label>
                                            <input type="file" class="form-control" name="surat_hasil" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">File Draft Skripsi</label>
                                            <input type="file" class="form-control" name="file_draft" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Bukti Izin</label>
                                            <input type="file" class="form-control" name="bukti_izin" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Lembar Jadwal</label>
                                            <input type="file" class="form-control" name="lembar_jadwal" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div> -->

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
        ['surat_hasil','file_draft','bukti_izin','lembar_jadwal'].forEach(field => {
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