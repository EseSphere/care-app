<?php require_once('header.php'); ?>
<!-- Features Section -->
<div class="main-wrapper">
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="./images/usericon.png" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h4>Samson Osaretin</h4>
                                    <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                                    <button class="btn btn-success">DNACPR</button>
                                    <button class="btn btn-danger">ALERGIES</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Prefered Pronoun</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    He/Him
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    samsonosaretin@yahoo.com
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    07448222483
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">City</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Wolverhampton
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Key safe</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    3342
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Date of Birth</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    09 July, 2025
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Required Services</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Domiciliary Care
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Medicine Support</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    We provide medicine support
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-danger " href="./report-issues.php">Report Issues</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page1.php" class="text-decoration-none text-black block bg-white">What is important to me</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page2.php" class="text-decoration-none text-black block bg-white">My likes and dislike</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page3.php" class="text-decoration-none text-black block bg-white">My current condition</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page4.php" class="text-decoration-none text-black block bg-white">My medical history</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page5.php" class="text-decoration-none text-black block bg-white">My physical health</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page6.php" class="text-decoration-none text-black block bg-white">My mental health</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page7.php" class="text-decoration-none text-black block bg-white">How I communicate</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./page8.php" class="text-decoration-none text-black block bg-white">Assistive equipment issues</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<button style="position: fixed; top:200px; right:15px;" class="btn btn-success">Start</button>
<script>
    let db;

    // Open IndexedDB
    function openDB() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open('OfflineDB', 1);

            request.onupgradeneeded = function(event) {
                db = event.target.result;
                if (!db.objectStoreNames.contains('contacts')) {
                    db.createObjectStore('contacts', {
                        keyPath: 'id',
                        autoIncrement: true
                    });
                }
            };

            request.onsuccess = function(event) {
                db = event.target.result;
                resolve(db);
            };

            request.onerror = function(event) {
                reject(event.target.error);
            };
        });
    }

    // Load all contacts and create cards
    function loadEmailCards() {
        const transaction = db.transaction('contacts', 'readonly');
        const store = transaction.objectStore('contacts');
        const request = store.openCursor();
        const container = document.getElementById('emailCards');
        container.innerHTML = '';

        request.onsuccess = function(event) {
            const cursor = event.target.result;
            if (cursor) {
                const contact = cursor.value;
                const card = `
            <div class="col-md-4 mb-4">
                <a href="view.php?email=${contact.email}" class="text-decoration-none text-dark">
                    <div class="card shadow p-3">
                        <div class="card-body">
                            <i class="bi bi-envelope-fill fs-1 text-primary"></i>
                            <h5 class="card-title mt-2">${contact.name}</h5>
                            <p class="card-text">${contact.email}</p>
                        </div>
                    </div>
                </a>
            </div>`;
                container.insertAdjacentHTML('beforeend', card);
                cursor.continue();
            }
        };

        request.onerror = function(event) {
            console.error('Error reading contacts:', event.target.error);
        };
    }

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            await openDB();
            loadEmailCards();
        } catch (err) {
            console.error('Failed to load emails:', err);
        }
    });
</script>

<?php require_once('footer.php'); ?>