<?php require_once('header.php'); ?>
<!-- Features Section -->
<div class="main-wrapper">
  <section class="container my-5">
    <div class="row text-left">

      <div class="col-md-6 col-sm-4 col-lg-6 mb-4">
        <a href="./care-plan.php" class="text-decoration-none" title="View Care Plan" target="_self" rel="noopener noreferrer" aria-label="Navigate to Care Plan page">
          <div class="card shadow p-3">
            <div class="row">
              <div class="col-9 text-start">
                <h5 class="card-title mt-2">Samson Osaretin</h5>
                <p class="card-text"><i class="bi bi-clock fw-bold"></i> 09:00 &#10132; 10:00</p>
              </div>
              <div class="col-3">
                <i class="bi bi-person-plus-fill text-black fs-2"></i>
              </div>
            </div>
          </div>
        </a>
      </div>

    </div>
  </section>

  <!-- Email Cards Section (Dynamic from IndexedDB) -->
  <section class="container my-5">
    <h2 class="text-center mb-4">Saved Contacts</h2>
    <div class="row text-center" id="emailCards">
      <!-- Cards will be inserted here dynamically -->
    </div>
  </section>
</div>

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