@extends('mahasiswa.layouts.app', ['activePage' => 'jadwal'])

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
<form id="formAbsensi" action="{{ route('mahasiswa.jadwal.simpan') }}" method="POST">
    @csrf
    <div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Scan QR Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-center">
                    <!-- Status Kamera -->
                    <div id="cameraStatus" class="mb-2 fw-bold text-secondary">
                        ⏳ Mengecek izin kamera...
                    </div>

                    <!-- Dropdown pilih kamera -->
                    <select id="cameraSelect" class="form-select mb-3"></select>

                    <!-- Scanner -->
                    <div id="reader"
                         style="width:100%; max-width:500px; margin:auto; border:1px solid #ccc;"></div>

                    <!-- Debug Panel -->
                    <div class="mt-3 text-start">
                        <label class="fw-bold">Debug Log:</label>
                        <pre id="debugLog"
                             style="height:150px; overflow:auto; background:#111; color:#0f0; padding:10px; font-size:12px; border-radius:5px; text-align:left;"></pre>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Hidden input -->
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                    <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->id }}">
                    
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" id="btnSimpanAbsensi" class="btn btn-primary">Simpan Absensi</button>
                </div>

            </div>
        </div>
    </div>
</form>

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
    const cameraStatus = document.getElementById("cameraStatus");
    const debugLog = document.getElementById("debugLog");
    let html5QrCode;

    function setCameraStatus(msg, type = "secondary") {
        cameraStatus.innerHTML = msg;
        cameraStatus.className = "mb-2 fw-bold text-" + type;
        logDebug("STATUS: " + msg);
    }

    function logDebug(msg) {
        let time = new Date().toLocaleTimeString();
        console.log("[DEBUG " + time + "] " + msg);
        debugLog.textContent += `[${time}] ${msg}\n`;
        debugLog.scrollTop = debugLog.scrollHeight;
    }

    function onScanSuccess(decodedText) {
        logDebug("QR Code detected: " + decodedText);
        alert("QR Terdeteksi: " + decodedText);
        window.location.href = decodedText;
    }

    async function startScanner(cameraId) {
        if (html5QrCode) {
            await html5QrCode.stop().catch(() => {});
            reader.innerHTML = "";
            logDebug("Scanner lama dihentikan.");
        }

        html5QrCode = new Html5Qrcode("reader");
        logDebug("Memulai scanner dengan kamera: " + cameraId);

        html5QrCode.start(
            cameraId,
            { fps: 10, qrbox: 250 },
            onScanSuccess,
            (errMsg) => logDebug("QR scan error: " + errMsg)
        ).then(() => {
            setCameraStatus("🟢 Kamera Aktif", "success");
        }).catch(err => {
            setCameraStatus("❌ Kamera gagal dijalankan: " + err, "danger");
            logDebug("Error startScanner: " + err);
        });
    }

    // buka modal
    document.getElementById('scannerModal').addEventListener('shown.bs.modal', async function () {
        debugLog.textContent = ""; // clear log setiap buka modal
        logDebug("Modal scanner dibuka.");

        if (!window.isSecureContext) {
            setCameraStatus("⚠️ Browser menolak akses kamera karena <b>bukan secure context</b>.<br>Gunakan <code>https://</code> atau akses via <code>http://localhost</code>.", "warning");
            logDebug("Browser tidak secure context.");
            return;
        }

        try {
            await navigator.mediaDevices.getUserMedia({ video: true });
            setCameraStatus("✅ Izin kamera diberikan", "success");

            const devices = await Html5Qrcode.getCameras();
            logDebug("Jumlah kamera ditemukan: " + devices.length);

            if (devices && devices.length) {
                cameraSelect.innerHTML = "";
                devices.forEach((device, idx) => {
                    let opt = document.createElement("option");
                    opt.value = device.id;
                    opt.text = device.label || `Camera ${idx + 1}`;
                    cameraSelect.appendChild(opt);
                });

                // auto pilih kamera belakang kalau ada
                let backCamera = devices.find(d => d.label.toLowerCase().includes("back"));
                let defaultCameraId = backCamera ? backCamera.id : devices[0].id;
                cameraSelect.value = defaultCameraId;

                logDebug("Default kamera: " + (backCamera ? "Belakang" : "Pertama"));
                startScanner(defaultCameraId);
            } else {
                setCameraStatus("❌ Tidak ada kamera ditemukan", "danger");
                logDebug("Tidak ada kamera ditemukan.");
            }
        } catch (err) {
            setCameraStatus("❌ Tidak bisa akses kamera: " + err.message, "danger");
            logDebug("Error akses kamera: " + err.message);
        }
    });

    // tutup modal
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', async function () {
        if (html5QrCode) {
            await html5QrCode.stop().catch(() => {});
            reader.innerHTML = "";
            logDebug("Scanner dimatikan.");
        }
        setCameraStatus("🔴 Kamera Mati", "danger");
        logDebug("Modal scanner ditutup.");
    });

    // ganti kamera manual
    cameraSelect.addEventListener("change", function () {
        logDebug("User memilih kamera: " + this.value);
        startScanner(this.value);
    });

    // tombol simpan absensi
    document.getElementById("btnSimpanAbsensi").addEventListener("click", function () {
        logDebug("User klik tombol simpan absensi.");

        fetch("{{ route('mahasiswa.jadwal.simpan') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                jadwal_id: "{{ $jadwal->id }}",
                mahasiswa_id: "{{ $mahasiswa->id }}"
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                setCameraStatus("✅ Absensi disimpan sebagai HADIR", "success");
                logDebug("Absensi berhasil disimpan.");
                alert("Absensi berhasil disimpan!");
            } else {
                setCameraStatus("⚠️ Gagal menyimpan absensi", "warning");
                logDebug("Respon gagal: " + JSON.stringify(data));
            }
        })
        .catch(err => {
            setCameraStatus("❌ Error simpan absensi", "danger");
            logDebug("Error simpan absensi: " + err.message);
        });
    });
});
</script>
@endsection