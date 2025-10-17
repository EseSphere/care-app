</div> <!-- end main-content -->

<div class="footer">
    <button onclick="history.back()" title="Back" id="btn-back"><i class="bi bi-arrow-left"></i></button>
    <a href="./home.php" title="Home"><i class="bi bi-house"></i></a>
    <a href="./visit-logs.php" title="Log"><i class="bi bi-journal-text"></i></a>
    <a href="./settings.php" title="User"><i class="bi bi-person"></i></a>
</div>

<!-- jQuery and Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>

<script>
    AOS.init();

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

        // Highlight clicked footer link
        $(".footer a, .footer button").click(function() {
            $(".footer a, .footer button").css("background", "transparent"); // Reset all
            $(this).css("background", "#22a6b3"); // Highlight clicked
        });

        // Optional: highlight the current page on load
        var currentPath = window.location.pathname.split("/").pop();
        $(".footer a").each(function() {
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
        width: 50px;
        height: 50px;
        border-radius: 10px;
        transition: background 0.3s;
    }
</style>
</body>

</html>