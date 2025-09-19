@extends('mahasiswa.layouts.app', [
'activePage' => 'jadwal',
])
@section('content')
<div class="page-header d-print-none">
   <div class="container-xl">
      <div class="row g-2 align-items-center">
         <div class="col">
            <div class="page-pretitle">Aplikasi FKG</div>
            <h2 class="page-title">Data Jadwal Absensi Mahasiswa</h2>
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
                        <h3 class="card-title">Pengeditan Data Jadwal Absensi Mahasiswa</h3>
                    </div>
                    <div class="card-body">

                        <!-- QR Code -->
                        <h3>QR Code Absen</h3>
                        <div class="mb-3">
                            {!! QrCode::size(200)->generate(route('mahasiswa.jadwal.scan', $jadwal->id)) !!}
                        </div>

                        <!-- Tombol buka scanner -->
                        <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#scannerModal">
                            Scan QR Absen
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scanner -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
          <!-- Dropdown pilih kamera -->
          <select id="cameraSelect" class="form-select mb-3"></select>
          <!-- Scanner -->
          <div id="reader" style="width:100%; max-width:500px; margin:auto;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- CDN Scanner -->
<script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    if (typeof Html5Qrcode === "undefined") {
        console.error("Html5Qrcode belum terload!");
        return;
    }

    const reader = document.getElementById("reader");
    const cameraSelect = document.getElementById("cameraSelect");
    let html5QrCode;

    function onScanSuccess(decodedText) {
        alert("QR Terdeteksi: " + decodedText);
        window.location.href = decodedText;
    }

    async function startScanner(cameraId) {
        if (html5QrCode) {
            await html5QrCode.stop().catch(()=>{});
            reader.innerHTML = "";
        }
        html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            (errMsg) => { console.warn("QR scan error:", errMsg); }
        ).catch(err => console.error("Start failed:", err));
    }

    // buka modal
    document.getElementById('scannerModal').addEventListener('shown.bs.modal', async function () {
        try {
            const devices = await Html5Qrcode.getCameras();
            if (devices && devices.length) {
                // isi dropdown
                cameraSelect.innerHTML = "";
                devices.forEach((device, idx) => {
                    let opt = document.createElement("option");
                    opt.value = device.id;
                    opt.text = device.label || `Camera ${idx+1}`;
                    cameraSelect.appendChild(opt);
                });

                // auto pilih kamera belakang kalau ada
                let backCamera = devices.find(d => d.label.toLowerCase().includes("back"));
                let defaultCameraId = backCamera ? backCamera.id : devices[0].id;
                cameraSelect.value = defaultCameraId;

                startScanner(defaultCameraId);
            } else {
                alert("Tidak ada kamera terdeteksi!");
            }
        } catch (err) {
            console.error("Init kamera gagal:", err);
            alert("Tidak bisa akses kamera: " + err);
        }
    });

    // tutup modal
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', async function () {
        if (html5QrCode) {
            await html5QrCode.stop().catch(()=>{});
            reader.innerHTML = "";
        }
    });

    // ganti kamera manual
    cameraSelect.addEventListener("change", function () {
        startScanner(this.value);
    });
});
</script>
@endsection