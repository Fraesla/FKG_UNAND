@extends('dosen.layouts.app', ['activePage' => 'jadwal'])

@section('content')

<style>
    .profile-card {
        background: #111827;
        border-radius: 14px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
        transition: 0.2s ease;
        border: 1px solid #1f2937;
        position: relative;
        z-index: 99999;
    }

    .profile-card:hover {
        transform: translateY(-2px);
    }

    .profile-img {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4b5563;
    }

    .profile-name {
        font-size: 15px;
        font-weight: 600;
        color: #fff;
    }

    .profile-sub {
        font-size: 12px;
        color: #9ca3af;
    }

    /* Dropdown custom */
    .menu-dropdown {
        position: absolute;
        top: 60px;
        right: 10px;
        width: 180px;

        background: #1f2937;
        border: 1px solid #374151;
        border-radius: 8px;
        padding: 6px 0;

        display: none; /* HIDDEN BY DEFAULT */
        opacity: 0;
        transform: translateY(-5px);
        transition: 0.2s ease;
        z-index: 999999;
    }

    .menu-dropdown.show {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .dropdown-item-btn {
        display: block;
        width: 100%;
        padding: 8px 14px;
        color: #e5e7eb;
        text-align: left;
        font-size: 14px;
        text-decoration: none;
    }

    .dropdown-item-btn:hover {
        background: #374151;
        color: white;
    }

</style>


<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3" style="z-index:999999; position: relative;">

        <h1>📅 Kalender Jadwal Absen Dosen</h1>

        <!-- ⭐ PROFILE CARD -->
        <div class="profile-card">

            <img src="{{ $dosen->foto ? asset('storage/'.$dosen->foto) : asset('assets/images/default-fkg.jpg') }}" width="50" height="50" class="rounded-circle object-cover profile-img">

            <div>
                <div class="profile-name">Nama Dosen : {{ $dosen->nama }}</div>
                <div class="profile-sub">NIP : {{ $dosen->nip }}</div>
            </div>

            <!-- CUSTOM MENU BUTTON -->
            <button id="toggleMenu" class="btn btn-sm" style="background: #1f2937; border:1px solid #374151; color:white;">
                ☰
            </button>

            <!-- CUSTOM MENU -->
            <div id="menuDropdown" class="menu-dropdown">
                <a href="/dosen/profile" class="dropdown-item-btn">Edit Profile</a>
                <a href="/dosen/changepass" class="dropdown-item-btn">Ubah Password</a>
            </div>

        </div>
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
            url: `/dosen/absendosen/isi/${e.id}`,
        })),

        eventClick: function(info) {
            info.jsEvent.preventDefault();
            if (info.event.url) window.location.href = info.event.url;
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

    // CUSTOM MENU HANDLER
    const toggleBtn = document.getElementById("toggleMenu");
    const dropdown = document.getElementById("menuDropdown");

    toggleBtn.addEventListener("click", () => {
        dropdown.classList.toggle("show");
    });

    // Close dropdown if click outside
    document.addEventListener("click", (e) => {
        if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove("show");
        }
    });
});
</script>