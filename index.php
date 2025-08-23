<?php require_once('header.php'); ?>

<!-- Hero Section -->
<section class="hero text-center py-5" style="background:#17a2b8;">
  <h1 class="text-white">Welcome to OfflineSite</h1>
  <p class="text-white">Fully offline-capable, modern, and responsive website</p>
  <a href="contact.php" class="btn btn-light btn-lg mt-3">Contact Us</a>
</section>

<!-- Features Section -->
<section class="container my-5">
  <div class="row text-center">
    <div class="col-md-4 mb-4">
      <div class="card shadow p-3">
        <div class="card-body">
          <i class="bi bi-cloud-arrow-down-fill fs-1 text-primary"></i>
          <h5 class="card-title mt-2">Offline Access</h5>
          <p class="card-text">Navigate pages even without internet connection.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card shadow p-3">
        <div class="card-body">
          <i class="bi bi-phone-fill fs-1 text-success"></i>
          <h5 class="card-title mt-2">Responsive Design</h5>
          <p class="card-text">Works on desktop, tablet, and mobile devices.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="card shadow p-3">
        <div class="card-body">
          <i class="bi bi-hdd-stack-fill fs-1 text-danger"></i>
          <h5 class="card-title mt-2">Dynamic Caching</h5>
          <p class="card-text">Automatically caches pages for smooth offline navigation.</p>
        </div>
      </div>
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