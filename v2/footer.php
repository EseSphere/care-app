</div> <!-- end main-content -->

<div class="footer">
    <button onclick="history.back()" title="Back" id="btn-back"><i class="bi bi-arrow-left"></i></button>
    <a href="./home.php" title="Home"><i class="bi bi-house"></i></a>
    <a href="./visit-logs.php" title="Log"><i class="bi bi-journal-text"></i></a>
    <a href="./timesheet.php" title="User"><i class="bi bi-list"></i></a>
</div>

<!-- jQuery and Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>
<!--<script src="./script.js"></script>-->

<script>
    AOS.init();

    // Prevent double-tap zoom
    let lastTouchEnd = 0;
    document.addEventListener('touchend', function(event) {
        const now = new Date().getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // Prevent pinch zoom
    ['gesturestart', 'gesturechange', 'gestureend'].forEach(evt => {
        document.addEventListener(evt, function(e) {
            e.preventDefault();
        });
    });

    // Force viewport to normal zoom if it changes
    function resetZoom() {
        const viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
        }
    }

    // Monitor for zoom or resize
    window.addEventListener('resize', resetZoom);
    window.addEventListener('orientationchange', resetZoom);

    // Clock
    function updateClock() {
        document.getElementById('topClock').textContent = new Date().toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    setInterval(updateClock, 1000);
    updateClock();

    document.addEventListener("gesturestart", function(e) {
        e.preventDefault();
        document.querySelector("meta[name=viewport]").setAttribute(
            "content",
            "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
        );
    });

    $(document).ready(function() {

        $("#btn-back").click(function() {
            window.history.back();
        });

        $("#menuBtn").click(function() {
            $("#sideNav, #overlay").toggleClass("active");
        });

        $("#overlay").click(function() {
            $("#sideNav, #overlay").removeClass("active");
        });

        // Highlight current page link in footer or sidebar
        var currentPath = window.location.pathname.split("/").pop();
        $("a[href]").each(function() {
            var linkPath = $(this).attr("href").split("/").pop();
            if (linkPath === currentPath) {
                $(this).css("background", "#22a6b3");
            }
        });

    });
</script>

<style>
    #sideNav {
        position: fixed;
        left: -250px;
        top: 0;
        width: 250px;
        height: 100%;
        background: #fff;
        z-index: 100;
        transition: 0.3s;
        overflow-y: auto;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    #sideNav.active {
        left: 0;
    }

    #overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99;
    }

    #overlay.active {
        display: block;
    }

    /* Footer link styles */
    .footer a,
    .footer button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 6px;
        transition: background 0.3s;
    }

    /* Sidebar link styles */
    #sideNav ul li a {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-radius: 8px;
        transition: background 0.3s, color 0.3s;
        color: #000;
        text-decoration: none;
    }

    #sideNav ul li a:hover {
        background: #22a6b3;
        color: #fff;
    }

    #sideNav ul li a i {
        margin-right: 8px;
    }
</style>
</body>

</html>