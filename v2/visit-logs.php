<?php include_once 'header.php'; ?>

<div class="main-wrapper container mt-3">

    <!-- Search & Filter Bar -->
    <div class="row search-filter-row">
        <div class="col-md-6">
            <input type="text" id="clientSearch" class="form-control" placeholder="Search by name or group...">
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

    <!-- Client List -->
    <div class="row" id="clientList"></div>
</div>

<script>
    const dbName = "care_app";
    const storeName = "tbl_daily_shift_records";

    const colors = ["#6c757d", "#0d6efd", "#198754", "#dc3545", "#ffc107", "#6f42c1", "#fd7e14"];
    const statusColors = {
        "Scheduled": "warning",
        "In-progress": "primary",
        "Completed": "success"
    };

    // Format date as dd Mon, hh:mm
    function formatDate(dateString) {
        const d = new Date(dateString);
        const day = d.getDate();
        const month = d.toLocaleString('en-US', {
            month: 'short'
        });
        const hours = d.getHours().toString().padStart(2, '0');
        const minutes = d.getMinutes().toString().padStart(2, '0');
        return `${day} ${month}, ${hours}:${minutes}`;
    }

    function getInitials(name) {
        const parts = name.split(' ');
        return (parts[0][0] + (parts[1]?.[0] || '')).toUpperCase();
    }

    // Open IndexedDB safely
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName);

            request.onerror = (e) => reject(e);
            request.onsuccess = (e) => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    reject(new Error(`Object store "${storeName}" not found in database "${dbName}"`));
                } else {
                    resolve(db);
                }
            };
        });
    }

    // Fetch most recent row per clientId (uryyToeSS4)
    function fetchClients() {
        openDB().then(db => {
            const tx = db.transaction(storeName, "readonly");
            const store = tx.objectStore(storeName);
            const request = store.getAll();

            request.onsuccess = () => {
                const data = request.result;

                // Group by clientId and select the most recent row
                const grouped = {};
                data.forEach(item => {
                    if (!grouped[item.uryyToeSS4] || new Date(item.dateTime) > new Date(grouped[item.uryyToeSS4].dateTime)) {
                        grouped[item.uryyToeSS4] = item;
                    }
                });

                // Convert to array and sort by last updated descending
                const clients = Object.values(grouped).sort((a, b) => new Date(b.dateTime) - new Date(a.dateTime));

                renderClients(clients);
            };

            request.onerror = (e) => console.error("Failed to read object store:", e);
        }).catch(err => console.error("Database error:", err));
    }

    // Render client cards
    function renderClients(clients) {
        const container = document.getElementById('clientList');
        container.innerHTML = "";

        clients.forEach(c => {
            const initials = getInitials(c.client_name);
            const color = colors[(initials.charCodeAt(0) + (initials.charCodeAt(1) || 65)) % colors.length];
            const statusColor = statusColors[c.col_call_status] || "secondary";

            const card = document.createElement('div');
            card.className = "col-md-6 col-lg-4 client-item";
            card.dataset.name = c.client_name.toLowerCase();
            card.dataset.group = c.client_group.toLowerCase();
            card.dataset.status = c.col_call_status;

            card.innerHTML = `
            <div class="card p-3 client-card card-hover d-flex flex-column" 
                 onclick="window.location='visits.php?uryyToeSS4=${c.uryyToeSS4}'"
                 style="transition:0.3s;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.08);cursor:pointer;">
                <div class="d-flex align-items-center mb-2">
                    <div style="width:60px;height:60px;border-radius:50%;background:${color};display:flex;align-items:center;justify-content:center;color:white;font-weight:bold;font-size:1.2rem;margin-right:15px;">
                        ${initials}
                    </div>
                    <div style="flex:1;">
                        <h6 class="mb-0" style="font-weight:600;">${c.client_name}</h6>
                        <small class="text-muted">${c.client_group}</small>
                        <div><span class="badge bg-${statusColor} mt-1">${c.col_call_status}</span></div>
                    </div>
                    <i class="bi bi-arrow-right-circle fs-4 text-info"></i>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted" style="font-size:.8rem;">Last updated: ${formatDate(c.dateTime)}</span>
                </div>
            </div>
        `;
            container.appendChild(card);
        });

        attachFilters();
    }

    // Attach search & status filters
    function attachFilters() {
        const searchInput = document.getElementById('clientSearch');
        const statusSelect = document.getElementById('statusFilter');
        const clientItems = document.querySelectorAll('.client-item');

        function filterClients() {
            const searchText = searchInput.value.toLowerCase();
            const selectedStatus = statusSelect.value;

            clientItems.forEach(item => {
                const name = item.dataset.name;
                const group = item.dataset.group;
                const status = item.dataset.status;

                const matchesSearch = name.includes(searchText) || group.includes(searchText);
                const matchesStatus = selectedStatus === "" || status === selectedStatus;

                item.style.display = (matchesSearch && matchesStatus) ? 'block' : 'none';
            });
        }

        searchInput.addEventListener('input', filterClients);
        statusSelect.addEventListener('change', filterClients);
    }

    // Initialize
    fetchClients();
</script>

<?php include_once 'footer.php'; ?>