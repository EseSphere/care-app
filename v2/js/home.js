


AOS.init();

// --- Local date helpers (avoid UTC bug) ---
function formatLocalISO(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${y}-${m}-${day}`;
}

function parseDateTimeLocal(dateStr, timeStr) {
    const [y, m, d] = dateStr.split('-').map(Number);
    const [hh = 0, mm = 0] = (timeStr || '').split(':').map(Number);
    return new Date(y, m - 1, d, hh, mm, 0);
}

// --- Initial setup ---
let selectedDate = formatLocalISO(new Date());
const dateStrip = document.getElementById('dateStrip');
const visitsContainer = document.getElementById('visitsContainer');
const avatarColors = ['#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4', '#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107', '#ff9800', '#ff5722', '#795548', '#607d8b'];

function getInitials(name) {
    const parts = name.split(' ');
    return (parts[0]?.[0] || '').toUpperCase() + (parts.at(-1)?.[0] || '').toUpperCase();
}

function getColorForName(name) {
    let hash = 0;
    for (let i = 0; i < name.length; i++) hash = name.charCodeAt(i) + ((hash << 5) - hash);
    return avatarColors[Math.abs(hash) % avatarColors.length];
}

// --- IndexedDB helpers ---
function openDB() {
    return new Promise((res, rej) => {
        const req = indexedDB.open('geosoft');
        req.onsuccess = e => res(e.target.result);
        req.onerror = e => rej(e.target.error);
    });
}

async function getUserSpecialId() {
    const db = await openDB();
    const tx = db.transaction('tbl_goesoft_carers_account', 'readonly');
    const store = tx.objectStore('tbl_goesoft_carers_account');
    const req = store.getAll();
    return new Promise((res, rej) => {
        req.onsuccess = e => {
            const users = e.target.result;
            res(users.length ? users[0].user_special_Id : null);
        };
        req.onerror = e => rej(e.target.error);
    });
}

async function getVisitsFromDB(userSpecialId) {
    const db = await openDB();
    const tx = db.transaction('tbl_schedule_calls', 'readonly');
    const store = tx.objectStore('tbl_schedule_calls');
    const visits = [];
    return new Promise((res, rej) => {
        const req = store.openCursor();
        req.onsuccess = e => {
            const cursor = e.target.result;
            if (cursor) {
                const v = cursor.value;
                if (v.first_carer_Id === userSpecialId) {
                    visits.push({
                        id: v.userId,
                        name: v.client_name,
                        service: v.client_area,
                        date: v.Clientshift_Date,
                        time_in: v.dateTime_in,
                        time_out: v.dateTime_out,
                        carers: parseInt(v.col_required_carers) || 1,
                        status: (v.call_status || '').toLowerCase(),
                        runName: v.col_run_name
                    });
                }
                cursor.continue();
            } else res(visits);
        };
        req.onerror = e => rej(e.target.error);
    });
}

// --- Core rendering ---
async function renderVisits() {
    const userSpecialId = await getUserSpecialId();
    if (!userSpecialId) {
        visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No user found</div>';
        return;
    }
    const all = await getVisitsFromDB(userSpecialId);

    // ✅ Filter strictly by selected date (Clientshift_Date)
    const filtered = all.filter(v => v.date === selectedDate)
        .sort((a, b) => (a.time_in || '').localeCompare(b.time_in || ''));

    renderVisitsFiltered(filtered);
    renderTimelineAndAlerts(filtered);
}

async function renderDatePills(centerDate) {
    dateStrip.innerHTML = '';
    const userSpecialId = await getUserSpecialId();
    const all = userSpecialId ? await getVisitsFromDB(userSpecialId) : [];
    const y = centerDate.getFullYear(),
        m = centerDate.getMonth();
    const daysInMonth = new Date(y, m + 1, 0).getDate();

    for (let i = 1; i <= daysInMonth; i++) {
        const d = new Date(y, m, i);
        const iso = formatLocalISO(d);
        const pill = document.createElement('div');
        pill.className = 'date-pill';
        pill.dataset.date = iso;
        pill.innerHTML = `<div style="font-weight:600">${d.toLocaleDateString(undefined, { weekday: 'short' })}</div>
                <div style="font-size:.85rem;position:relative">${d.getDate()} ${d.toLocaleString(undefined, { month: 'short' })}</div>`;
        if (all.some(v => v.date === iso)) {
            const dot = document.createElement('div');
            dot.style.cssText = 'width:6px;height:6px;background:#bdc3c7;border-radius:50%;position:absolute;bottom:-5px;left:50%;transform:translateX(-50%)';
            pill.querySelector('div:nth-child(2)').appendChild(dot);
        }
        if (iso === selectedDate) pill.classList.add('active');
        pill.addEventListener('click', () => {
            selectedDate = iso;
            renderDatePills(centerDate);
            renderVisits();
            setTimeout(scrollToActiveDate, 100);
        });
        dateStrip.appendChild(pill);
    }
    setTimeout(scrollToActiveDate, 100);
}

function scrollToActiveDate() {
    const active = document.querySelector('.date-pill.active');
    if (active) active.scrollIntoView({
        behavior: 'smooth',
        inline: 'center'
    });
}

function updateQuickStats(v) {
    document.getElementById('countCalls').textContent = v.length;
}

function updateProgress(v) {
    const bar = document.getElementById('progressBar');
    if (!v.length) {
        bar.style.width = '0%';
        return;
    }
    const done = v.filter(x => x.status === 'completed').length;
    bar.style.width = Math.round(done / v.length * 100) + '%';
}

function renderVisitsFiltered(v) {
    visitsContainer.innerHTML = '';
    if (!v.length) {
        visitsContainer.innerHTML = '<div class="text-center small-muted p-5">No visits found</div>';
        document.getElementById('totalHours').textContent = '0h 0m';
        updateQuickStats([]);
        updateProgress([]);
        document.getElementById('runName').textContent = 'N/A';
        return;
    }
    const tpl = document.getElementById('visitTpl');
    let total = 0;
    v.forEach(vis => {
        const node = document.importNode(tpl.content, true);
        const card = node.querySelector('.card');
        card.style.cursor = 'pointer';
        card.addEventListener('click', () => location.href = `care-plan?userId=${vis.id}`);
        const initials = getInitials(vis.name);
        const color = getColorForName(vis.name);
        const av = node.querySelector('.avatar');
        av.innerHTML = `<div class="avatar-initials" style="background:${color};color:#fff;font-weight:bold;border-radius:.5rem;width:4rem;height:4rem;display:flex;align-items:center;justify-content:center;font-size:1.8rem">${initials}</div>`;
        node.querySelector('.name').textContent = vis.name;
        node.querySelector('.service').textContent = vis.service;
        node.querySelector('.times').textContent = `${vis.time_in} - ${vis.time_out}`;
        const carers = node.querySelector('.carers-icons');
        carers.innerHTML = '';
        if (vis.carers === 2) carers.innerHTML = '👥';
        else if (vis.carers > 2) carers.innerHTML = '👤'.repeat(vis.carers);

        const badge = node.querySelector('.status');
        badge.textContent = vis.status || 'scheduled';
        badge.className = 'badge badge-status ms-1';
        if (vis.status === 'scheduled') badge.classList.add('bg-info', 'text-dark');
        if (vis.status === 'in-progress') badge.classList.add('bg-warning', 'text-dark');
        if (vis.status === 'completed') badge.classList.add('bg-success');

        const start = parseDateTimeLocal(vis.date, vis.time_in);
        const end = parseDateTimeLocal(vis.date, vis.time_out);
        total += (end - start) / 60000;
        const now = new Date();
        if (start > now && start - now <= 3600000) card.style.border = '2px solid var(--accent2)';
        if (start < now && vis.status !== 'completed') card.style.border = '2px solid red';
        visitsContainer.appendChild(node);
    });
    const h = Math.floor(total / 60),
        m = Math.floor(total % 60);
    document.getElementById('totalHours').textContent = `${h}h ${m}m`;
    updateQuickStats(v);
    updateProgress(v);
    document.getElementById('runName').textContent = v[0]?.runName || 'N/A';
}

function renderTimelineAndAlerts(v) {
    const alerts = document.getElementById('alertsContainer');
    alerts.innerHTML = '';
    const now = new Date();
    v.forEach(x => {
        const s = parseDateTimeLocal(x.date, x.time_in);
        const e = parseDateTimeLocal(x.date, x.time_out);
        const div = document.createElement('div');
        if (s > now && s - now <= 3600000) {
            div.className = 'alert-item text-info';
            div.textContent = `Upcoming: ${x.name} at ${x.time_in}`;
        } else if (e < now && x.status !== 'completed') {
            div.className = 'alert-item text-danger';
            div.textContent = `Overdue: ${x.name} (${x.time_in}-${x.time_out})`;
        }
        if (div.textContent) alerts.appendChild(div);
    });
    if (!alerts.hasChildNodes()) alerts.textContent = 'No alerts for today';
}

function updateClock() {
    document.getElementById('today-clock').textContent = new Date().toLocaleTimeString([], {
        hour: '2-digit',
        minute: '2-digit'
    });
}
setInterval(updateClock, 1000);
updateClock();

// --- Navigation buttons ---
renderDatePills(new Date());
renderVisits();
setTimeout(scrollToActiveDate, 100);

document.getElementById('prevDay').onclick = () => {
    const d = new Date(selectedDate + 'T00:00');
    d.setDate(d.getDate() - 1);
    selectedDate = formatLocalISO(d);
    renderDatePills(d);
    renderVisits();
    setTimeout(scrollToActiveDate, 100);
};
document.getElementById('nextDay').onclick = () => {
    const d = new Date(selectedDate + 'T00:00');
    d.setDate(d.getDate() + 1);
    selectedDate = formatLocalISO(d);
    renderDatePills(d);
    renderVisits();
    setTimeout(scrollToActiveDate, 100);
};
document.getElementById('todayBtn').onclick = () => {
    selectedDate = formatLocalISO(new Date());
    renderDatePills(new Date());
    renderVisits();
    setTimeout(scrollToActiveDate, 100);
};
document.getElementById('refreshBtn').onclick = () => renderVisits();

document.getElementById('searchVisits').addEventListener('input', async e => {
    const t = e.target.value.toLowerCase();
    const userSpecialId = await getUserSpecialId();
    if (!userSpecialId) return;
    const all = await getVisitsFromDB(userSpecialId);
    const filtered = all.filter(v => v.date === selectedDate && v.name.toLowerCase().includes(t))
        .sort((a, b) => (a.time_in || '').localeCompare(b.time_in || ''));
    renderVisitsFiltered(filtered);
    renderTimelineAndAlerts(filtered);
});

// --- Offline indicators ---
window.addEventListener('offline', () => document.getElementById('offlineStatus').style.display = 'inline-block');
window.addEventListener('online', () => {
    document.getElementById('offlineStatus').style.display = 'none';
    localStorage.removeItem('offlineQueue');
});

const menuBtn = document.getElementById('menuBtn');
const sideNav = document.getElementById('sideNav');
const overlay = document.getElementById('overlay');
menuBtn.onclick = () => {
    sideNav.classList.add('open');
    overlay.classList.add('show');
};
overlay.onclick = () => {
    sideNav.classList.remove('open');
    overlay.classList.remove('show');
};