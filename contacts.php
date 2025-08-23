<?php require_once('header.php'); ?>

<section class="hero" style="background:#6c757d;">
    <h1>Saved Messages</h1>
    <p>All messages stored offline in IndexedDB</p>
</section>

<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <table class="table table-striped" id="contactsTable">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <a href="contact.php" class="btn btn-primary mt-3 w-100">Add New Message</a>
        </div>
    </div>
</section>

<script>
    let db;

    // Open IndexedDB and ensure it's ready
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

    // Load all contacts and display in table
    function loadContacts() {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction('contacts', 'readonly');
            const store = transaction.objectStore('contacts');
            const request = store.openCursor();
            const tbody = $('#contactsTable tbody');
            tbody.empty();

            request.onsuccess = function(event) {
                const cursor = event.target.result;
                if (cursor) {
                    const row = `<tr>
                    <td>${cursor.value.id}</td>
                    <td>${cursor.value.name}</td>
                    <td>${cursor.value.email}</td>
                    <td>${cursor.value.message}</td>
                    <td>${new Date(cursor.value.createdAt).toLocaleString()}</td>
                </tr>`;
                    tbody.append(row);
                    cursor.continue();
                } else {
                    resolve(); // Finished reading all entries
                }
            };

            request.onerror = function(event) {
                console.error('Error reading contacts:', event.target.error);
                reject(event.target.error);
            };
        });
    }

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            await openDB();
            await loadContacts();
        } catch (err) {
            console.error('Failed to load contacts:', err);
        }
    });
</script>

<?php require_once('footer.php'); ?>