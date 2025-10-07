@extends('admin.layouts.app', [
'activePage' => 'yudisium',
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
                  <h2 class="page-title">Data Yudisium</h2>
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
                                    Penambahan Data Yudisium
                                </h3>
                                <a href="/admin/yudisium/" class="btn btn-secondary btn-sm">
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
                                <form action="/admin/yudisium/create" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="space-y">
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
                                            <label class="form-label">Judul</label>
                                            <input type="text" placeholder="Masukkan Judul" class="form-control" name="judul" />
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Proposal</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Proposal" id="datepicker-icon-prepend" name="tgl_semi_proposal">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="form-label">Tanggal Seminar Hasil</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon"><!-- Download SVG icon from http://tabler.io/icons/icon/calendar -->
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"></path>
                                                    <path d="M16 3v4"></path>
                                                    <path d="M8 3v4"></path>
                                                    <path d="M4 11h16"></path>
                                                    <path d="M11 15h1"></path>
                                                    <path d="M12 15v3"></path></svg></span>
                                                <input class="form-control" placeholder="Masukkan Tanggal Seminar Hasil" id="datepicker-icon-prepend_2" name="tgl_semi_hasil">
                                            </div>
                                        </div>
                                        <!-- <div>
                                            <label class="form-label">Hasil Turnitin</label>
                                            <input type="file" class="form-control" name="hasil_turnitin" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Bukti Lunas</label>
                                            <input type="file" class="form-control" name="bukti_lunas" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KHS</label>
                                            <input type="file" class="form-control" name="khs" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">KBS</label>
                                            <input type="file" class="form-control" name="kbs" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">BrSempro</label>
                                            <input type="file" class="form-control" name="brsempro" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Brsemhas</label>
                                            <input type="file" class="form-control" name="brsemhas" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Full Skripsi</label>
                                            <input type="file" class="form-control" name="full_skripsi" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">Matriks</label>
                                            <input type="file" class="form-control" name="matriks" accept=".pdf,.doc,.docx,.jpg,.png"/>
                                        </div>
                                        <div>
                                            <label class="form-label">TOEFL</label>
                                            <input type="file" class="form-control" name="toefl" accept=".pdf,.doc,.docx,.jpg,.png"/>
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
        ['hasil_turnitin','bukti_lunas','khs','kbs','brsempro','brsemhas','full_skripsi','matriks','toefl'].forEach(field => {
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