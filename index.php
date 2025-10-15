<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PIN Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body { display:flex; height:100vh; justify-content:center; align-items:center; background:#f5f6fa; }
    .login-box { width: 350px; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
    .status { margin-top:10px; font-size:14px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h4 class="text-center mb-4">Enter 4-Digit PIN</h4>
    <form id="loginForm">
      <div class="form-group">
        <input type="password" id="pin" class="form-control text-center" maxlength="4" pattern="\d{4}" required>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <p id="status" class="status text-center text-muted"></p>
  </div>

  <script>
    const API_URL = "/api/login.php"; // change to your backend login endpoint
    const statusEl = document.getElementById("status");

    // Check if already logged in (cached PIN)
    window.addEventListener("load", () => {
      const cachedUser = localStorage.getItem("user");
      if (cachedUser) {
        statusEl.textContent = "Offline login available";
      } else {
        statusEl.textContent = "First login requires internet";
      }
    });

    document.getElementById("loginForm").addEventListener("submit", async (e) => {
      e.preventDefault();
      const pin = document.getElementById("pin").value;

      if (!navigator.onLine) {
        // Offline mode
        const cachedUser = JSON.parse(localStorage.getItem("user") || "{}");
        if (cachedUser.pin === pin) {
          statusEl.textContent = "Offline login success ✅";
          window.location.href = "visits.html";
        } else {
          statusEl.textContent = "Offline login failed ❌";
        }
        return;
      }

      // Online mode - validate with backend
      try {
        statusEl.textContent = "Checking online...";
        let res = await fetch(API_URL, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ pin })
        });
        let data = await res.json();

        if (data.success) {
          // Save user offline
          localStorage.setItem("user", JSON.stringify({ pin, token: data.token }));
          statusEl.textContent = "Login success ✅";
          window.location.href = "visits.html";
        } else {
          statusEl.textContent = "Invalid PIN ❌";
        }
      } catch (err) {
        console.error(err);
        statusEl.textContent = "Server error ❌";
      }
    });
  </script>
</body>
</html>
