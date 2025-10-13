<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">
    <!-- Client Profile Horizontal Layout -->
    <?php require_once 'client-profile-extension.php'; ?>

    <!-- Search & Filter Bar -->
    <div class="row search-filter-row">
        <div class="col-md-6">
            <input type="text" id="clientSearch" class="form-control" placeholder="Search by carer">
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <option value="Scheduled">Scheduled</option>
                <option value="In-progress">In-progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
    </div>

    <!-- Visits List -->
    <h5>Visit History</h5>
    <div class="row g-3" id="visitsContainer"></div>

    <hr>
    <!-- Client Highlights -->
    <?php require_once 'highlight-extention.php'; ?>
</div>

<script>
    const dbName = "care_app";
    const clientStoreName = "tbl_general_client_form";
    const visitsStoreName = "tbl_daily_shift_records";

    const careCallColors = {
        'Morning': 'warning',
        'Lunch': 'danger',
        'Tea': 'tea',
        'Bed': 'dark'
    };

    // --- Utility Functions ---
    const getClientIdFromUrl = () => {
        return new URLSearchParams(window.location.search).get('uryyToeSS4');
    };

    const openDB = (storeName) => {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName);
            request.onerror = e => reject(e);
            request.onsuccess = e => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    reject(new Error(`Object store "${storeName}" not found`));
                } else {
                    resolve(db);
                }
            };
        });
    };

    const calculateAge = dobStr => {
        if (!dobStr) return '--';
        const dob = new Date(dobStr);
        const diff = Date.now() - dob.getTime();
        return Math.abs(new Date(diff).getUTCFullYear() - 1970);
    };

    // --- Display Functions ---
    const displayClientInfo = client => {
        document.getElementById('clientName').textContent = `${client.client_first_name} ${client.client_last_name}`;
        document.getElementById('clientAge').textContent = `Age: ${calculateAge(client.client_date_of_birth)}`;

        const initialsEl = document.getElementById('clientInitials');
        const initials = (client.client_first_name[0] || '-') + (client.client_last_name[0] || '-');
        initialsEl.textContent = initials.toUpperCase();
        initialsEl.style.backgroundColor = '#0d6efd';

        // Preserve paragraphs and line breaks
        const highlightEl = document.getElementById('highlight');
        if (client.client_highlights) {
            const formattedText = client.client_highlights
                .split(/\n\s*\n/) // split into paragraphs
                .map(para => `<p>${para.replace(/\n/g, '<br>')}</p>`) // single line breaks
                .join('');
            highlightEl.innerHTML = formattedText;
        } else {
            highlightEl.textContent = 'No highlights';
        }
    };

    const renderVisits = visits => {
        const container = document.getElementById('visitsContainer');
        container.innerHTML = '';

        visits.forEach((v, index) => {
            const carers = v.carer_Name ? v.carer_Name.split(',') : [];
            const carerAvatars = carers.map(c => `
            <span class="d-inline-flex align-items-center me-2 mb-1">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(c)}&background=random" alt="${c}" class="carers-avatar2">
                <span class="carer-name">${c}</span>
            </span>
        `).join('');

            container.innerHTML += `
            <div class="col-md-6 col-lg-4">
                <div class="card p-3 visit-card card-hover h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-${careCallColors[v.col_care_call] || 'info'}">${v.col_care_call}</span>
                        <small class="text-muted">${new Date(v.shift_date).toLocaleDateString('en-GB', { day:'2-digit', month:'short', year:'numeric' })}</small>
                    </div>
                    <div class="mb-2">
                        <i class="bi bi-clock me-1"></i>
                        <strong>In:</strong> ${v.planned_timeIn || '-'}
                        <strong class="ms-2">Out:</strong> ${v.planned_timeOut || '-'}
                    </div>
                    <div class="mb-2">
                        <strong>Carers:</strong>
                        <div class="d-flex flex-wrap align-items-center mt-1">
                            ${carerAvatars}
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>Note</strong>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#noteCollapse${index}" aria-expanded="false" aria-controls="noteCollapse${index}">
                                <i class="bi bi-chevron-down rotate-icon collapsed"></i>
                            </button>
                        </div>
                        <div class="collapse mt-2" id="noteCollapse${index}">
                            <div class="border rounded p-2">${v.note || ''}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        });

        attachCollapseRotate();
        attachSearchFilter();
    };

    const attachCollapseRotate = () => {
        document.querySelectorAll('.collapse').forEach(c => {
            c.addEventListener('show.bs.collapse', () => {
                const icon = c.previousElementSibling.querySelector('.rotate-icon');
                if (icon) icon.classList.remove('collapsed');
            });
            c.addEventListener('hide.bs.collapse', () => {
                const icon = c.previousElementSibling.querySelector('.rotate-icon');
                if (icon) icon.classList.add('collapsed');
            });
        });
    };

    const attachSearchFilter = () => {
        const searchInput = document.getElementById('clientSearch');
        const statusSelect = document.getElementById('statusFilter');
        const visitCards = document.querySelectorAll('.visit-card');

        const filterVisits = () => {
            const searchText = searchInput.value.toLowerCase();
            const selectedStatus = statusSelect.value.toLowerCase();

            visitCards.forEach(card => {
                const call = card.querySelector('.badge').textContent.toLowerCase();
                const carers = Array.from(card.querySelectorAll('.carer-name')).map(el => el.textContent.toLowerCase()).join(' ');

                const matchesSearch = carers.includes(searchText) || call.includes(searchText);
                const matchesStatus = !selectedStatus || call === selectedStatus;

                card.parentElement.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
            });
        };

        searchInput.addEventListener('input', filterVisits);
        statusSelect.addEventListener('change', filterVisits);
    };

    // --- Data Fetching ---
    const fetchClient = async clientId => {
        try {
            const db = await openDB(clientStoreName);
            const tx = db.transaction(clientStoreName, 'readonly');
            const store = tx.objectStore(clientStoreName);
            const request = store.openCursor();

            let found = false;
            request.onsuccess = e => {
                const cursor = e.target.result;
                if (cursor) {
                    if (cursor.value.uryyToeSS4 === clientId) {
                        displayClientInfo(cursor.value);
                        found = true;
                    } else cursor.continue();
                } else if (!found) {
                    document.getElementById('clientName').textContent = "Client not found";
                    document.getElementById('clientAge').textContent = "Age: --";
                    document.getElementById('highlight').textContent = "N/A";
                }
            };
            request.onerror = e => console.error("Failed to fetch client:", e);
        } catch (err) {
            console.error("Database error:", err);
        }
    };

    const fetchVisits = async clientId => {
        try {
            const db = await openDB(visitsStoreName);
            const tx = db.transaction(visitsStoreName, 'readonly');
            const store = tx.objectStore(visitsStoreName);
            const request = store.getAll();

            request.onsuccess = () => {
                const clientVisits = request.result
                    .filter(v => v.uryyToeSS4 === clientId)
                    .sort((a, b) => new Date(b.shift_date + " " + b.planned_timeIn) - new Date(a.shift_date + " " + a.planned_timeIn));
                renderVisits(clientVisits);
            };
            request.onerror = e => console.error("Failed to fetch visits:", e);
        } catch (err) {
            console.error("Database error:", err);
        }
    };

    // --- Update Action Links ---
    const updateActionLinks = clientId => {
        if (!clientId) return;
        document.getElementById('dnacprBtn').href = `health.php?uryyToeSS4=${encodeURIComponent(clientId)}`;
        document.getElementById('allergiesBtn').href = `emergency.php?uryyToeSS4=${encodeURIComponent(clientId)}`;
    };

    // --- Initialization ---
    const clientId = getClientIdFromUrl();
    if (clientId) {
        fetchClient(clientId);
        fetchVisits(clientId);
        updateActionLinks(clientId);
    } else {
        console.error("No client ID found in URL");
    }
</script>

<?php include_once 'footer.php'; ?>