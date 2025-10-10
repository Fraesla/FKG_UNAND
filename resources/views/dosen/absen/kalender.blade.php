@extends('dosen.layouts.app', ['activePage' => 'jadwal'])

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>📅 Kalender Jadwal Absen Dosen</h1>
        <!-- <div class="d-flex align-items-center gap-2">
            <select id="filterDosen" class="form-select form-select-sm" style="width:200px">
                <option value="">Semua Dosen</option>
                @foreach($dosen as $d)
                    <option value="{{ $d->nama }}">{{ $d->nama }}</option>
                @endforeach
            </select>
            <a href="#" class="btn btn-primary btn-sm">
                + Tambah Absen
            </a>
        </div> -->
    </div>

    <div id="calendar" style="min-height: 80vh;"></div>
</div>
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");
    const allEvents = @json($events);

    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        height: "auto",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek"
        },
        events: allEvents.map(e => ({
            ...e,
            url: `/dosen/absendosen/isi/${e.id}`, // arahkan ke halaman isi absen
        })),
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) {
                window.location.href = info.event.url;
            }
        },
        eventDidMount: function(info) {
            new bootstrap.Tooltip(info.el, {
                title: `
                    <strong>${info.event.title}</strong><br>
                    📅 ${info.event.start.toLocaleDateString()}<br>
                    ⏰ Masuk: ${info.event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}<br>
                    ⏰ Pulang: ${info.event.end ? info.event.end.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '-'}<br>
                    📝 Keterangan: ${info.event.extendedProps.keterangan ?? '-'}
                `,
                html: true,
                placement: 'top',
                trigger: 'hover'
            });
        }
    });

    calendar.render();

    // ✅ Fitur filter dosen
    const filterDosen = document.getElementById("filterDosen");
    filterDosen.addEventListener("change", function() {
        const nama = this.value;
        const filteredEvents = nama 
            ? allEvents.filter(e => e.title.includes(nama))
            : allEvents;

        calendar.removeAllEvents();
        calendar.addEventSource(filteredEvents.map(e => ({
            ...e,
            url: `/dosen/absen/isi/${e.id}`
        })));
    });
});
</script>