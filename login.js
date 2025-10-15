let db;
let request = indexedDB.open("appDB", 1);

request.onupgradeneeded = function (e) {
    db = e.target.result;
    if (!db.objectStoreNames.contains("users")) {
        db.createObjectStore("users", { keyPath: "id" });
    }
};

request.onsuccess = function (e) {
    db = e.target.result;
    if (navigator.onLine) loadUsersOffline();
};

function loadUsersOffline() {
    fetch("get-users.php")  // <- relative path, not /api/login.php
        .then(res => res.json())
        .then(users => {
            let tx = db.transaction("users", "readwrite");
            let store = tx.objectStore("users");
            users.forEach(u => store.put(u));
        })
        .catch(err => console.error("Failed to load users:", err));
}

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();
    let username = document.getElementById("username").value;
    let pin = document.getElementById("pin").value;

    if (navigator.onLine) {
        fetch("../login.php", {   // <- relative path
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `username=${encodeURIComponent(username)}&pin=${encodeURIComponent(pin)}`
        })
            .then(res => {
                if (!res.ok) throw new Error("Network response was not ok");
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    localStorage.setItem("user", JSON.stringify(data.user));
                    window.location = "visits.html";
                } else {
                    document.getElementById("message").innerText = "Invalid login";
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById("message").innerText = "Login failed. Try offline mode.";
            });
    } else {
        let tx = db.transaction("users", "readonly");
        let store = tx.objectStore("users");
        let found = false;

        store.openCursor().onsuccess = function (e) {
            let cursor = e.target.result;
            if (cursor) {
                let u = cursor.value;
                if (u.name === username && u.pin === pin) {
                    found = true;
                    localStorage.setItem("user", JSON.stringify(u));
                    window.location = "visits.html";
                    return;
                }
                cursor.continue();
            } else {
                if (!found) document.getElementById("message").innerText = "Invalid login (offline)";
            }
        };
    }
});
