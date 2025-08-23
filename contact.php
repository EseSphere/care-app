<?php require_once('header.php'); ?>

<section class="hero" style="background:#17a2b8;">
  <h1>Contact Us</h1>
  <p>Submit your message offline</p>
</section>

<section class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <form id="contactForm">
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" required>
        </div>
        <div class="mb-3">
          <label for="message" class="form-label">Message</label>
          <textarea class="form-control" id="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-send-fill"></i> Send Message</button>
      </form>
      <div id="successMessage" class="alert alert-success mt-3 d-none">
        Your message has been saved offline.
      </div>
      <a href="contacts.php" class="btn btn-secondary mt-3 w-100">View All Messages</a>
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

  // Save data into IndexedDB
  function saveContact(data) {
    return new Promise((resolve, reject) => {
      const transaction = db.transaction('contacts', 'readwrite');
      const store = transaction.objectStore('contacts');
      const request = store.add(data);

      request.onsuccess = () => resolve();
      request.onerror = (event) => reject(event.target.error);
    });
  }

  document.addEventListener('DOMContentLoaded', async () => {
    try {
      await openDB();

      const form = document.getElementById('contactForm');
      form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const contactData = {
          name: document.getElementById('name').value,
          email: document.getElementById('email').value,
          message: document.getElementById('message').value,
          createdAt: new Date()
        };

        try {
          await saveContact(contactData);
          document.getElementById('successMessage').classList.remove('d-none');
          form.reset();
        } catch (err) {
          console.error('Failed to save contact:', err);
        }
      });

    } catch (err) {
      console.error('Failed to open database:', err);
    }
  });
</script>

<?php require_once('footer.php'); ?>