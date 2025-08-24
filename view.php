<?php require_once('header.php'); ?>

<section class="hero text-center py-5" style="background:#17a2b8;">
    <h1 class="text-white">View Contact</h1>
    <p class="text-white">Full details of the selected contact</p>
    <a href="index.php" class="btn btn-light btn-lg mt-3">Back to Home</a>
</section>

<section class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-4" id="contactDetails">
                <!-- Contact details will be inserted here -->
            </div>
        </div>
    </div>
</section>

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

    // Get query parameter from URL
    function getQueryParam(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Load contact by email
    function loadContactByEmail(email) {
        const transaction = db.transaction('contacts', 'readonly');
        const store = transaction.objectStore('contacts');
        const request = store.openCursor();
        const container = document.getElementById('contactDetails');

        let found = false;
        request.onsuccess = function(event) {
            const cursor = event.target.result;
            if (cursor) {
                if (cursor.value.email === email) {
                    const contact = cursor.value;
                    container.innerHTML = `
          <h3>${contact.name}</h3>
          <p><strong>Email:</strong> ${contact.email}</p>
          <p><strong>Message:</strong> ${contact.message}</p>
          <p><strong>Date:</strong> ${new Date(contact.createdAt).toLocaleString()}</p>
        `;
                    found = true;
                    return; // Stop iteration
                }
                cursor.continue();
            } else {
                if (!found) {
                    container.innerHTML = '<p class="text-danger">Contact not found.</p>';
                }
            }
        };

        request.onerror = function(event) {
            console.error('Failed to load contact:', event.target.error);
            container.innerHTML = '<p class="text-danger">Error loading contact.</p>';
        };
    }

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            await openDB();
            const email = getQueryParam('email');
            if (email) {
                loadContactByEmail(email);
            } else {
                document.getElementById('contactDetails').innerHTML = '<p class="text-danger">No contact selected.</p>';
            }
        } catch (err) {
            console.error('Error opening database:', err);
        }
    });
</script>

<?php require_once('footer.php'); ?>