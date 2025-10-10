
function calculateAge(dob) {
    if (!dob) return '--';
    const birthDate = new Date(dob);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    const dayDiff = today.getDate() - birthDate.getDate();
    if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) age--;
    return age;
}

async function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('geosoft');
        request.onsuccess = e => resolve(e.target.result);
        request.onerror = e => reject(e.target.error);
    });
}

function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

async function getVisitById(userId) {
    if (!userId) return null;
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('tbl_schedule_calls', 'readonly');
        const store = tx.objectStore('tbl_schedule_calls');
        const req = store.getAll();
        req.onsuccess = e => {
            const visit = e.target.result.find(v => v.userId == userId);
            resolve(visit || null);
        };
        req.onerror = e => reject(e.target.error);
    });
}

async function getVisitsForClient(uryyToeSS4) {
    if (!uryyToeSS4) return [];
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('tbl_schedule_calls', 'readonly');
        const store = tx.objectStore('tbl_schedule_calls');
        const req = store.getAll();
        req.onsuccess = e => {
            const visits = e.target.result.filter(v => v.uryyToeSS4 === uryyToeSS4);
            resolve(visits);
        };
        req.onerror = e => reject(e.target.error);
    });
}

async function getClientDetails(uryyToeSS4) {
    if (!uryyToeSS4) return null;
    const db = await openDB();
    return new Promise((resolve, reject) => {
        const tx = db.transaction('tbl_general_client_form', 'readonly');
        const store = tx.objectStore('tbl_general_client_form');
        const req = store.getAll();
        req.onsuccess = e => {
            const client = e.target.result.find(c => c.uryyToeSS4 === uryyToeSS4);
            resolve(client || null);
        };
        req.onerror = e => reject(e.target.error);
    });
}

function createInitialsCircle(fullName, fontSize = 2, diameter = 100) {
    if (!fullName) fullName = '--';
    const names = fullName.split(' ');
    const initials = ((names[0]?.charAt(0) || '') + (names[1]?.charAt(0) || '')).toUpperCase();
    const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
    const charCodeSum = (initials.charCodeAt(0) || 0) + (initials.charCodeAt(1) || 0);
    const bgColor = colors[charCodeSum % colors.length];

    const div = document.createElement('div');
    div.textContent = initials;
    div.style.width = `${diameter}px`;
    div.style.height = `${diameter}px`;
    div.style.borderRadius = '50%';
    div.style.display = 'flex';
    div.style.alignItems = 'center';
    div.style.justifyContent = 'center';
    div.style.fontSize = `${fontSize}rem`;
    div.style.fontWeight = 'bold';
    div.style.color = 'white';
    div.style.backgroundColor = bgColor;
    div.style.marginBottom = '5px';
    return div;
}

async function renderCarePlan() {
    const userId = getQueryParam('userId');
    if (!userId) {
        document.body.innerHTML = '<div class="text-center p-5">No visit selected.</div>';
        return;
    }

    const visit = await getVisitById(userId);
    if (!visit) {
        document.body.innerHTML = '<div class="text-center p-5">Visit not found.</div>';
        return;
    }

    const uryyToeSS4 = visit.uryyToeSS4;
    if (!uryyToeSS4) return;

    // Fetch all visits for this client
    const clientVisits = await getVisitsForClient(uryyToeSS4);

    // Total visits
    document.getElementById('visitsToday').textContent = clientVisits.filter(v => v.Clientshift_Date === new Date().toISOString().slice(0, 10)).length;
    document.getElementById('pendingTasks').textContent = visit.col_run_name || 'N/A';

    // Assigned carers
    const carersSet = new Set();
    clientVisits.forEach(v => {
        if (v.first_carer) carersSet.add(v.first_carer);
        if (v.second_carer) carersSet.add(v.second_carer);
    });
    document.getElementById('totalCarers').textContent = carersSet.size;

    const assignedCarers = [];
    carersSet.forEach(name => assignedCarers.push({
        name,
        role: 'Carer'
    }));
    const carersContainer = document.getElementById('carersContainer');
    carersContainer.innerHTML = '';
    assignedCarers.forEach(c => {
        const col = document.createElement('div');
        col.className = 'col-6 col-sm-4 col-md-3 col-lg-2 text-center mb-3';
        const card = document.createElement('div');
        card.className = 'd-flex flex-column align-items-center p-2';
        const initialsCircle = createInitialsCircle(c.name, 1.5, 80);
        card.appendChild(initialsCircle);
        const nameEl = document.createElement('strong');
        nameEl.style.fontSize = '.9rem';
        nameEl.textContent = c.name;
        card.appendChild(nameEl);
        const roleEl = document.createElement('small');
        roleEl.className = 'text-muted';
        roleEl.textContent = c.role;
        card.appendChild(roleEl);
        col.appendChild(card);
        carersContainer.appendChild(col);
    });

    // Client details
    const client = await getClientDetails(uryyToeSS4);
    if (client) {
        const firstName = client.client_first_name || '';
        const lastName = client.client_last_name || '';

        const initialsDiv = document.getElementById('clientInitials');
        const clientInitialsCircle = createInitialsCircle(`${firstName} ${lastName}`, 2, 100);
        initialsDiv.replaceWith(clientInitialsCircle);
        clientInitialsCircle.id = 'clientInitials';

        document.getElementById('clientName').textContent = `${firstName} ${lastName}`;
        document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;
        document.getElementById('clientEmail').textContent = client.client_email_address || '--';
        document.getElementById('clientPhone').textContent = client.client_primary_phone || '--';
        document.getElementById('clientKeySafe').textContent = client.client_access_details || '--';
        document.getElementById('clientCity').textContent = client.client_city || '--';
        document.getElementById('clientPronoun').textContent = client.client_sexuality || '--';
        document.getElementById('dateofbirth').textContent = client.client_date_of_birth || '--';
        document.getElementById('condition').textContent = client.client_ailment || '--';
        document.getElementById('gender').textContent = client.client_sexuality || '--';

        const address = `${client.client_address_line_1 || ''}, ${client.client_address_line_2 || ''}, ${client.client_city || ''}`;
        document.getElementById('clientAddress').href = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
        document.getElementById('clientAddress').querySelector('div').textContent = address;

        document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${client.uryyToeSS4}`;
        document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${client.uryyToeSS4}`;

        // ✅ Set Start Shift button based on checkin_type
        const startBtn = document.getElementById('startShiftBtn');
        if (visit.checkin_type === 'qrcode') {
            startBtn.href = `checkin-qrcode.php?userId=${visit.userId}`;
        } else if (visit.checkin_type === 'geolocation') {
            startBtn.href = `checkin-geolocation.php?userId=${visit.userId}`;
        } else {
            startBtn.href = '#';
            startBtn.classList.add('disabled');
        }

        const highlightDiv = document.getElementById('highlight');
        if (client.client_highlights) {
            const paragraphs = client.client_highlights.split(/\n\s*\n/);
            highlightDiv.innerHTML = paragraphs.map(p => `<p>${p.trim().replace(/\n/g, '<br>')}</p>`).join('');
        } else {
            highlightDiv.innerHTML = '<p>Loading...</p>';
        }
    }

    // Assessment Cards
    if (uryyToeSS4) {
        const assessments = [{
            title: 'What is important to me',
            link: `./wiitm.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-heart'
        },
        {
            title: 'My likes and dislikes',
            link: `./mlad.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-emoji-smile'
        },
        {
            title: 'My current condition',
            link: `./mcc.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-activity'
        },
        {
            title: 'My medical history',
            link: `./mmh.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-journal-medical'
        },
        {
            title: 'My physical health',
            link: `./mph.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-heart-pulse'
        },
        {
            title: 'My mental health',
            link: `./mmh.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-brain'
        },
        {
            title: 'How I communicate',
            link: `./hic.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-chat-left-text'
        },
        {
            title: 'Assistive equipment issues',
            link: `./aei.php?uryyToeSS4=${uryyToeSS4}`,
            icon: 'bi-tools'
        }
        ];

        const assessmentContainer = document.getElementById('assessmentCards');
        assessmentContainer.innerHTML = '';
        assessments.forEach(a => {
            const card = document.createElement('div');
            card.className = 'card mb-2 p-3 assessment-card';
            card.innerHTML = `<a href="${a.link}"><i class="bi ${a.icon} me-2"></i>${a.title}</a>`;
            assessmentContainer.appendChild(card);
        });
    }
}

renderCarePlan();
