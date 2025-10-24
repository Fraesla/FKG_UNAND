@extends('mahasiswa.layouts.app', ['activePage' => 'jadwal'])

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📅 Kalender Jadwal Absen Mahasiswa</h1>
    </div>

    <div id="calendar" style="min-height: 80vh;"></div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");

    // ✅ Pastikan $events dikonversi ke JSON valid
    const allEvents = @json($events ?? []);
    console.log("Loaded events:", allEvents);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: "auto",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek"
        },
        events: allEvents,
        eventDidMount: function(info) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                new bootstrap.Tooltip(info.el, {
                    title: `
                        <strong>${info.event.extendedProps.nama_dosen}</strong><br>
                        📅 ${info.event.extendedProps.tgl}<br>
                        ⏰ Masuk: ${info.event.extendedProps.jam_masuk}<br>
                        ⏰ Pulang: ${info.event.extendedProps.jam_pulang}<br>
                        📝 ${info.event.extendedProps.keterangan}
                    `,
                    html: true,
                    placement: 'top',
                    trigger: 'hover'
                });
            }
        }
    });

    calendar.render();
});
</script>