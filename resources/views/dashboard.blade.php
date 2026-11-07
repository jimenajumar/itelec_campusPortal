@extends('layouts.app')
@section('title', 'Dashboard - ACES')
@section('content')
      <!-- Banner -->
      <section class="relative min-h-[450px] flex items-center overflow-hidden bg-blue-950">
        <!-- Campus Background -->
        <div class="absolute inset-0">
            <img src="{{  asset('images/aces building.png') }}"
            alt="ACES Tagum College Campus"
            class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-950/70"></div>
        </div>
        <!-- Hero Content -->
        <div class="relative z-10 px-8 md:px-16 py-20 text-white">
            <h1 class="text-4x1 md:text-6x1 font-bold">WELCOME BACK</h1>
            <div class="w-20 h-1 bg-red-600 my-5"></div>
            <p class="text-lg md:text-x1 tracking-wider">
                Your Student Journey Starts Here.
            </p>
            <span class="inline-block mt-7 bg-red-700 px-6 py-3 rounded-ful font-semibold">Student Dashboard</span>
        </div>
        <!-- Curved Bottom Accent-->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg viewBox="0 0 1440 160"
            class="w-full h-20 md:h-28"
            preserveAspectRatio="none">
            <!-- Red wave -->
            <path d="M0,75 C350, 145 850,10 1440,65 L1440,160 L0,160 Z"
            fill="#991b1b"/>
            <!-- white Wave -->
            <path d="M0,105 C400, 165 950,45 1440,95 L1440,160 L0,160 Z"
            fill="white"/>
        </div>
      </section>

<!-- CAMPUS UPDATES SLIDER -->
<section class="mt-6 overflow-hidden rounded-2xl border border-white/20
                bg-indigo-950/75 p-6 text-white shadow-xl backdrop-blur-md">

    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-xl font-bold">Campus Updates</h2>
        <span class="text-xs text-indigo-200">Latest updates</span>
    </div>

    <div id="campusSlider" class="relative min-h-40">
        <!-- Slide 1 -->
<!-- Slide 1 -->
        <div class="campus-slide rounded-xl bg-indigo-800/80 p-6">
         <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-indigo-200">
        Latest Events
        </p>

            <div id="latestEvent">
            <h3 class="text-2xl font-bold">Loading events...</h3>
            <p class="mt-2 text-sm text-indigo-100">
            Please wait while we load campus events.
            </p>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="campus-slide hidden rounded-xl bg-sky-800/80 p-6">
            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-sky-200">
                Today's Weather
            </p>
            <h3 class="text-2xl font-bold">Weather Updates</h3>
            <p class="mt-2 text-sm text-sky-100">
                Check the latest weather conditions for your school day.
            </p>
        </div>

        <!-- Slide 3 -->
        <div class="campus-slide hidden rounded-xl bg-rose-900/80 p-6">
            <p class="mb-2 text-xs font-semibold uppercase tracking-widest text-rose-200">
                Announcements
            </p>
            <h3 class="text-2xl font-bold">Stay Informed!</h3>
            <p class="mt-2 text-sm text-rose-100">
                Check the latest announcements from the school administration.
            </p>
        </div>
    </div>

    <!-- Slider indicators -->
    <div class="mt-4 flex justify-center gap-2">
        <button class="slider-dot h-2.5 w-2.5 rounded-full bg-white"
                onclick="showCampusSlide(0)" aria-label="Show events"></button>
        <button class="slider-dot h-2.5 w-2.5 rounded-full bg-white/40"
                onclick="showCampusSlide(1)" aria-label="Show weather"></button>
        <button class="slider-dot h-2.5 w-2.5 rounded-full bg-white/40"
                onclick="showCampusSlide(2)" aria-label="Show announcements"></button>
    </div>
</section>


<!-- SCHOOL CALENDAR -->
<section class="mt-6 rounded-2xl border border-white/30
                bg-white/90 p-6 text-slate-800 shadow-xl backdrop-blur-md">

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-medium text-indigo-700">ACES Tagum College</p>
            <h2 class="text-2xl font-bold">School Calendar</h2>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="changeMonth(-1)"
                    class="rounded-lg bg-indigo-100 px-3 py-2 font-bold text-indigo-900 hover:bg-indigo-200">
                &lt;
            </button>

            <h3 id="calendarMonth" class="min-w-32 text-center font-bold"></h3>

            <button onclick="changeMonth(1)"
                    class="rounded-lg bg-indigo-100 px-3 py-2 font-bold text-indigo-900 hover:bg-indigo-200">
                &gt;
            </button>
        </div>
    </div>

    <!-- Calendar grid -->
    <div class="grid grid-cols-7 gap-2 text-center">
        <div class="text-sm font-bold text-slate-500">Sun</div>
        <div class="text-sm font-bold text-slate-500">Mon</div>
        <div class="text-sm font-bold text-slate-500">Tue</div>
        <div class="text-sm font-bold text-slate-500">Wed</div>
        <div class="text-sm font-bold text-slate-500">Thu</div>
        <div class="text-sm font-bold text-slate-500">Fri</div>
        <div class="text-sm font-bold text-slate-500">Sat</div>
    </div>

    <div id="calendarDays" class="mt-2 grid grid-cols-7 gap-2"></div>

    <!-- Calendar legend -->
    <div class="mt-5 flex flex-wrap gap-4 text-xs text-slate-600">
        <span><span class="mr-1 inline-block h-3 w-3 rounded-full bg-rose-500"></span> Holiday</span>
        <span><span class="mr-1 inline-block h-3 w-3 rounded-full bg-indigo-500"></span> School Event</span>
        <span><span class="mr-1 inline-block h-3 w-3 rounded-full bg-emerald-500"></span> Today</span>
    </div>

    <!-- Selected date details -->
    <div class="mt-5 rounded-xl bg-indigo-50 p-4">
        <h3 id="selectedDate" class="font-bold text-indigo-950">
            Select a date
        </h3>
        <div id="dateEvents" class="mt-2 text-sm text-slate-600">
            Click a date to see its events and holidays.
        </div>
    </div>
</section>


<script>
// CAMPUS UPDATES SLIDER
let currentCampusSlide = 0;

const campusSlider = document.getElementById("campusSlider");
const campusSlides = document.querySelectorAll(".campus-slide");
const campusDots = document.querySelectorAll(".slider-dot");

function showCampusSlide(index) {
    if (!campusSlider || campusSlides.length === 0) return;

    currentCampusSlide = index;

    // Slide horizontally
    campusSlider.style.transform =
        `translateX(-${index * 100}%)`;

    // Update navigation dots
    campusDots.forEach((dot, i) => {
        dot.classList.toggle("bg-white", i === index);
        dot.classList.toggle("bg-white/40", i !== index);
    });
}

// Automatically slide every 5 seconds
setInterval(() => {
    showCampusSlide(
        (currentCampusSlide + 1) % campusSlides.length
    );
}, 5000);

// FETCH EVENTS FROM LARAVEL API
async function loadCampusEvents() {
    const token = sessionStorage.getItem('campus_token');

    if (!token) {
        console.error('Login token not found.');
        return;
    }

    try {
        const response = await fetch('/api/events', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            console.error('Events request failed:', response.status);
            return;
        }

        const events = await response.json();

        console.log('Campus events:', events);

const eventContainer = document.getElementById('latestEvent');

if (events.length > 0) {
    const event = events[0];

    eventContainer.innerHTML = `
        <h3 class="text-2xl font-bold">${event.title}</h3>
        <p class="mt-2 text-sm text-indigo-100">
            ${event.description || 'No description available.'}
        </p>
        <p class="mt-3 text-xs text-indigo-200">
            Venue: ${event.venue || 'To be announced'}
        </p>
        <p class="mt-1 text-xs text-indigo-200">
            Date: ${new Date(event.starts_at).toLocaleDateString()}
        </p>
    `;
} else {
    eventContainer.innerHTML = `
        <h3 class="text-2xl font-bold">No upcoming events</h3>
        <p class="mt-2 text-sm text-indigo-100">
            Check back for new campus activities.
        </p>
    `;
}

    } catch (error) {
        console.error('Error loading events:', error);
    }
}

loadCampusEvents();


// SCHOOL CALENDAR
let calendarDate = new Date();
let selectedCalendarDate = new Date(
    calendarDate.getFullYear(),
    calendarDate.getMonth(),
    calendarDate.getDate()
);

// Sample dates for demonstration.
// Replace these with database events later.
const calendarEvents = {
    "2026-11-30": [
        { name: "Bonifacio Day", type: "holiday" }
    ],
    "2026-12-25": [
        { name: "Christmas Day", type: "holiday" }
    ],
    "2027-01-01": [
        { name: "New Year's Day", type: "holiday" }
    ],
};

function formatDateKey(year, month, day) {
    return year + "-" +
        String(month + 1).padStart(2, "0") + "-" +
        String(day).padStart(2, "0");
}

function renderCalendar() {
    const year = calendarDate.getFullYear();
    const month = calendarDate.getMonth();

    document.getElementById("calendarMonth").textContent =
        calendarDate.toLocaleDateString("en-US", {
            month: "long",
            year: "numeric"
        });

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const calendarDays = document.getElementById("calendarDays");
    calendarDays.innerHTML = "";

    for (let i = 0; i < firstDay; i++) {
        calendarDays.innerHTML += '<div class="min-h-12"></div>';
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateKey = formatDateKey(year, month, day);
        const events = calendarEvents[dateKey] || [];

        const isToday =
            year === new Date().getFullYear() &&
            month === new Date().getMonth() &&
            day === new Date().getDate();

        const isSelected =
            selectedCalendarDate.getFullYear() === year &&
            selectedCalendarDate.getMonth() === month &&
            selectedCalendarDate.getDate() === day;

        let marker = "";

        if (events.some(e => e.type === "holiday")) {
            marker = '<span class="mx-auto mt-1 block h-2 w-2 rounded-full bg-rose-500"></span>';
        } else if (events.some(e => e.type === "event")) {
            marker = '<span class="mx-auto mt-1 block h-2 w-2 rounded-full bg-indigo-500"></span>';
        }

        let button = document.createElement("button");

        button.className =
            "min-h-12 rounded-lg p-1 text-sm transition hover:bg-indigo-100 " +
            (isToday ? "bg-emerald-100 font-bold text-emerald-800 " : "bg-slate-50 ") +
            (isSelected ? "ring-2 ring-indigo-600 " : "");

        button.innerHTML = '<span>' + day + '</span>' + marker;

        button.addEventListener("click", function () {
            selectedCalendarDate = new Date(year, month, day);
            renderCalendar();
            showDateEvents(dateKey);
        });

        calendarDays.appendChild(button);
    }

    showDateEvents(formatDateKey(
        selectedCalendarDate.getFullYear(),
        selectedCalendarDate.getMonth(),
        selectedCalendarDate.getDate()
    ));
}

function showDateEvents(dateKey) {
    const date = new Date(dateKey + "T00:00:00");

    document.getElementById("selectedDate").textContent =
        date.toLocaleDateString("en-US", {
            month: "long",
            day: "numeric",
            year: "numeric"
        });

    const events = calendarEvents[dateKey] || [];
    const details = document.getElementById("dateEvents");

    details.innerHTML = "";

    if (events.length === 0) {
        details.textContent =
            "No scheduled events or holidays for this date.";
        return;
    }

    events.forEach(event => {
        const card = document.createElement("div");
        card.className =
            "mb-3 rounded-lg border border-gray-200 bg-white p-3";

        const title = document.createElement("h4");
        title.className = "font-bold text-blue-950";
        title.textContent =
            (event.type === "holiday" ? "🎉 " : "📅 ") + event.name;

        card.appendChild(title);
        if (event.description) {
            const description = document.createElement("p");
            description.className = "mt-2 text-sm text-gray-600";
            description.textContent = event.description;
            card.appendChild(description);
}

        if (event.venue) {
            const venue = document.createElement("p");
            venue.className = "mt-1 text-sm text-gray-500";
            venue.textContent = "📍 Venue: " + event.venue;
            card.appendChild(venue);
        }

        if (event.starts_at) {
            const time = document.createElement("p");
            time.className = "mt-1 text-sm text-gray-500";
            time.textContent =
                "🕒 Starts: " +
                new Date(event.starts_at).toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit"
                });
            card.appendChild(time);
        }
        if (event.ends_at) {
            const endTime = document.createElement("p");
            endTime.className = "mt-1 text-sm text-gray-500";

            endTime.textContent =
                "🕒 Ends: " +
            new Date(event.ends_at).toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit"
        });

    card.appendChild(endTime);
}

        details.appendChild(card);
    });
}

function changeMonth(direction) {
    calendarDate.setMonth(calendarDate.getMonth() + direction);
    renderCalendar();
}
async function loadCalendarEvents() {
    const token = sessionStorage.getItem('campus_token');

    if (!token) {
        console.error("Login token not found.");
        renderCalendar();
        return;
    }

    try {
        const response = await fetch('/api/events', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            throw new Error("Unable to load calendar events.");
        }

        const events = await response.json();

        events.forEach(event => {
            if (!event.starts_at) return;

            const dateKey = event.starts_at.substring(0, 10);

            if (!calendarEvents[dateKey]) {
                calendarEvents[dateKey] = [];
            }

            calendarEvents[dateKey].push({
                name: event.title,
                type: "event",
                venue: event.venue,
                description: event.description,
                starts_at: event.start_at,
                ends_at: event.ends_at,
            });
        });

    } catch (error) {
        console.error("Calendar error:", error);
    }

    renderCalendar();
}

loadCalendarEvents();
</script>

</body>
</html>
@endsection
