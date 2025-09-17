<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>General Observation — Professional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --accent: #4A90E2;
            --accent2: #50C9C3;
            --muted: #6c757d;
            --bg1: #f7faff;
            --bg2: #eef5ff;
        }

        body {
            background: linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
            font-family: Inter, sans-serif;
            color: #333;
            overflow-x: hidden;
            padding-bottom: 80px;
        }

        body.dark-mode {
            background: #1e1e2f;
            color: #ccc;
        }

        body.dark-mode .card {
            background: #2a2a3d;
            color: #ccc;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: linear-gradient(90deg, var(--accent), var(--accent2));
            padding: 12px 15px;
            border-radius: 12px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar h4 {
            margin: 0;
            font-weight: 600;
            text-align: center;
            flex: 1;
        }

        .menu-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.4rem;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: #fff;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -3px 10px rgba(0, 0, 0, .1);
            z-index: 30;
            border-top: 1px solid #eee;
        }

        .footer button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #e0e0e0;
            font-size: 1.5rem;
            color: #4A90E2;
            transition: background .2s, color .2s;
        }

        .footer button:hover {
            background: #4A90E2;
            color: white;
        }

        #sideNav {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background: #fff;
            box-shadow: 2px 0 12px rgba(0, 0, 0, .2);
            z-index: 50;
            transition: left .3s ease;
            padding: 1.5rem;
        }

        #sideNav.open {
            left: 0;
        }

        #overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .4);
            z-index: 40;
            display: none;
        }

        #overlay.show {
            display: block;
        }

        .main-wrapper {
            padding: 20px;
        }

        .card {
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(30, 40, 60, .06);
            margin-bottom: 15px;
        }

        #previewModal .modal-body {
            white-space: pre-wrap;
        }

        #allergiesBtn,
        #dnacprBtn {
            font-weight: 800;
            color: red;
            text-decoration: none;
        }

        .note-thumbnail {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            margin-top: 5px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- SideNav (like activity-report.php) -->
    <div id="sideNav">
        <div class="user-info text-center mb-4">
            <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mb-2" width="80" alt="Profile">
            <h5>Samson Gift</h5>
            <small>samsonosaretin@yahoo.com</small>
            <p>07448222483</p>
        </div>
        <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-calendar-event me-2"></i>Visits</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-graph-up me-2"></i>Reports</a></li>
            <li class="mb-2"><a href="#" class="d-block p-2"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li class="mt-4"><button class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button></li>
        </ul>
    </div>

    <div id="overlay"></div>

    <!-- Topbar -->
    <div class="topbar">
        <button class="menu-btn" id="menuBtn"><i class="bi bi-list"></i></button>
        <h4>Observation</h4>
        <div class="d-flex align-items-center gap-3">
            <span id="topClock"></span>
            <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
            <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
        </div>
    </div>

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
                    <label for="obsDateTime" class="form-label">Date & Time</label>
                    <input type="datetime-local" class="form-control" id="obsDateTime" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Urgency</label>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urgency" id="urgencyLow" value="Low" checked>
                            <label class="form-check-label" for="urgencyLow">Low</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urgency" id="urgencyMedium" value="Medium">
                            <label class="form-check-label" for="urgencyMedium">Medium</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="urgency" id="urgencyHigh" value="High">
                            <label class="form-check-label" for="urgencyHigh">High</label>
                        </div>
                    </div>
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
                <h5>Recent Notes / Observations</h5>
                <div id="notesContainer"></div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <button title="Back"><i class="bi bi-arrow-left"></i></button>
        <button title="Home"><i class="bi bi-house"></i></button>
        <button title="Log"><i class="bi bi-journal-text"></i></button>
        <button title="User"><i class="bi bi-person"></i></button>
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
        // SideNav toggle
        const menuBtn = document.getElementById('menuBtn');
        const sideNav = document.getElementById('sideNav');
        const overlay = document.getElementById('overlay');
        menuBtn.addEventListener('click', () => {
            sideNav.classList.add('open');
            overlay.classList.add('show');
        });
        overlay.addEventListener('click', () => {
            sideNav.classList.remove('open');
            overlay.classList.remove('show');
        });

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
            const dateTime = document.getElementById('obsDateTime').value;
            const urgency = document.querySelector('input[name="urgency"]:checked').value;
            const med = document.getElementById('medicationCheck').checked ? '[Medication]' : '';
            const anon = document.getElementById('anonymousNote').checked ? '[Anonymous] ' : '';
            const photoInput = document.getElementById('obsPhoto');
            let photoURL = '';

            if (photoInput.files && photoInput.files[0]) {
                photoURL = URL.createObjectURL(photoInput.files[0]);
            }

            if (!text || !dateTime) return;

            recentNotes.unshift({
                author: 'You',
                time: dateTime,
                text: `${anon}${med} [Urgency: ${urgency}] ${text}`,
                photo: photoURL
            });
            renderNotes();
            obsForm.reset();

            // 🔹 Redirect directly to checkout
            window.location.href = "check-out.php";
        });


        // Preview Modal
        const previewBtn = document.querySelector('[data-bs-target="#previewModal"]');
        previewBtn.addEventListener('click', () => {
            const text = document.getElementById('observationText').value.trim();
            const dateTime = document.getElementById('obsDateTime').value;
            const urgency = document.querySelector('input[name="urgency"]:checked').value;
            const med = document.getElementById('medicationCheck').checked ? '[Medication]' : '';
            const anon = document.getElementById('anonymousNote').checked ? '[Anonymous] ' : '';
            document.getElementById('previewContent').textContent = `${anon}${med} [Urgency: ${urgency}] ${text}\nDate & Time: ${dateTime}`;
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>