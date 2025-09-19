<?php include_once 'header.php'; ?>

<div class="main-wrapper container">
    <!-- Client Profile Card -->
    <div class="col-md-12 mb-3">
        <div class="card p-3 d-flex flex-row align-items-center justify-content-between">
            <div style="flex:1;">
                <h4 id="clientName">Duru Artrick</h4>
                <p id="clientLocation" class="text-muted mb-1">Bay Area, San Francisco, CA</p>
                <div class="d-flex gap-2">
                    <a href="#" id="dnacprBtn">DNACPR</a>
                    <a href="#" id="allergiesBtn">ALLERGIES</a>
                </div>
            </div>
        </div>
    </div>

    <!-- General Observation Form -->
    <div class="card p-3 mb-3">
        <h5>Observation</h5>
        <hr>
        <form id="observationForm">
            <div class="mb-3">
                <label for="observationText" class="form-label">Observation</label>
                <textarea class="form-control" id="observationText" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="obsPhoto" class="form-label">Attach Photo</label>
                <input class="form-control" type="file" id="obsPhoto" accept="image/*">
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="medicationCheck">
                <label class="form-check-label" for="medicationCheck">Related to Medication</label>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="anonymousNote">
                <label class="form-check-label" for="anonymousNote">Submit Anonymously</label>
            </div>
            <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#previewModal">Preview</button>
            <button type="submit" class="btn btn-primary mb-2">Submit</button>
        </form>
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

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Observation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="previewContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
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
    const recentNotes = [];
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
            if (n.photo) {
                photoHTML = `<img src="${n.photo}" class="note-thumbnail">`;
            }

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

    // Observation Form Submit
    const obsForm = document.getElementById('observationForm');
    obsForm.addEventListener('submit', e => {
        e.preventDefault();
        const text = document.getElementById('observationText').value.trim();
        const med = document.getElementById('medicationCheck').checked ? '[Medication]' : '';
        const anon = document.getElementById('anonymousNote').checked ? '[Anonymous] ' : '';
        const photoInput = document.getElementById('obsPhoto');
        let photoURL = '';

        if (photoInput.files && photoInput.files[0]) {
            photoURL = URL.createObjectURL(photoInput.files[0]);
        }

        if (!text) return;

        recentNotes.unshift({
            author: 'You',
            time: new Date().toISOString(),
            text: `${anon}${med} ${text}`,
            photo: photoURL
        });
        renderNotes();
        obsForm.reset();

        // Redirect directly to checkout
        window.location.href = "check-out";
    });

    // Preview Modal
    const previewBtn = document.querySelector('[data-bs-target="#previewModal"]');
    previewBtn.addEventListener('click', () => {
        const text = document.getElementById('observationText').value.trim();
        const med = document.getElementById('medicationCheck').checked ? '[Medication]' : '';
        const anon = document.getElementById('anonymousNote').checked ? '[Anonymous] ' : '';
        document.getElementById('previewContent').textContent = `${anon}${med} ${text}`;
    });
</script>

<?php include_once 'footer.php'; ?>