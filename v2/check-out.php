<?php include_once 'header.php'; ?>

<div class="main-wrapper container">
    <!-- Client Profile Card -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
            <div style="flex:1;">
                <h4 id="clientName">Duru Artrick</h4>
                <p id="clientLocation" class="text-muted mb-1">Bay Area, San Francisco, CA</p>
                <p><strong>Check-In:</strong> <span id="checkInTime">09:00 AM</span></p>
            </div>
            <a href="./home" class="btn btn-success text-decoration-none" id="checkoutBtn">
                Check-Out
            </a>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="card p-3 mb-3">
        <h5>Summary</h5>
        <hr>
        <p>All observations and notes have been successfully submitted.</p>
    </div>

    <!-- Assigned Carers -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Assigned Carers</h5>
            <div class="d-flex flex-wrap gap-3" id="carersContainer"></div>
        </div>
    </div>

    <!-- Recent Notes -->
    <div class="col-md-12 mt-3">
        <div class="card p-3">
            <h5>Recent Notes</h5>
            <div id="notesContainer"></div>
        </div>
    </div>

</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
    <div id="checkoutToast" class="toast align-items-center text-white bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body">Check-Out Completed Successfully!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
    // Clock
    function updateClock() {
        document.getElementById('topClock').textContent = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Dark Mode
    const darkBtn = document.getElementById('darkModeBtn');
    darkBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
    });

    // Assigned Carers
    const assignedCarers = [{
            name: 'Alice Johnson',
            role: 'Primary Carer',
            phone: '07440111222',
            img: 'https://randomuser.me/api/portraits/women/45.jpg'
        },
        {
            name: 'John Smith',
            role: 'Backup Carer',
            phone: '07440111333',
            img: 'https://randomuser.me/api/portraits/men/56.jpg'
        }
    ];
    const carersContainer = document.getElementById('carersContainer');
    assignedCarers.forEach(c => {
        const div = document.createElement('div');
        div.className = 'd-flex flex-column align-items-center text-center p-2';
        div.style.width = '120px';
        div.innerHTML = `
                <div style="width:80px;height:80px;border-radius:50%;overflow:hidden;margin-bottom:5px;">
                    <img src="${c.img}" style="width:100%;height:100%;object-fit:cover;" alt="${c.name}">
                </div>
                <strong style="font-size:.9rem;">${c.name}</strong>
                <small class="text-muted">${c.role}</small>
                <a href="tel:${c.phone}" class="btn btn-sm btn-outline-success mt-1">Call</a>
            `;
        carersContainer.appendChild(div);
    });

    // Recent Notes
    const recentNotes = [{
        author: 'You',
        time: new Date(),
        text: '[Medication] Observation note example',
        photo: ''
    }];
    const notesContainer = document.getElementById('notesContainer');

    function renderNotes() {
        notesContainer.innerHTML = '';
        recentNotes.forEach(n => {
            const noteDiv = document.createElement('div');
            noteDiv.className = 'mb-2 p-2';
            noteDiv.style.borderBottom = '1px solid #eee';
            const date = new Date(n.time);
            const formatted = date.toLocaleDateString(undefined, {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            let photoHTML = '';
            if (n.photo) photoHTML = `<img src="${n.photo}" class="note-thumbnail">`;
            noteDiv.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${n.author}</strong>
                        <small class="text-muted">${formatted}</small>
                    </div>
                    <div>${n.text}</div>
                    ${photoHTML}
                `;
            notesContainer.appendChild(noteDiv);
        });
    }
    renderNotes();

    // Confirm Check-Out
    const confirmBtn = document.getElementById('confirmCheckoutBtn');
    confirmBtn.addEventListener('click', () => {
        const finalNote = document.getElementById('finalNote').value.trim();
        if (finalNote) {
            recentNotes.unshift({
                author: 'You',
                time: new Date(),
                text: finalNote,
                photo: ''
            });
            renderNotes();
        }

        const modalEl = document.getElementById('confirmCheckoutModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal.hide();

        const toastEl = document.getElementById('checkoutToast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        setTimeout(() => {
            window.location.href = 'home.php';
        }, 2000);
    });
</script>

<?php include_once 'footer.php'; ?>