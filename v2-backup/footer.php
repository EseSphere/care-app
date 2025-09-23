</div> <!-- end main-content -->

<div class="footer">
    <button onclick="history.back()" title="Back" id="btn-back"><i class="bi bi-arrow-left"></i></button>
    <a href="./home" title="Home" data-page="home"><i class="bi bi-house"></i></a>
    <a href="./visit-logs" title="Log" data-page="visit-logs"><i class="bi bi-journal-text"></i></a>
    <a href="./settings" title="User" data-page="settings"><i class="bi bi-person"></i></a>
</div>

<!-- jQuery and Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>

<script>
    AOS.init();

    // Force zoom reset
    document.addEventListener("gesturestart", function(e) {
        e.preventDefault();
        document.querySelector("meta[name=viewport]").setAttribute(
            "content",
            "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
        );
    });

    $(document).ready(function() {

        // SPA page loader
        function loadPage(page) {
            $("#main-content").fadeOut(200, function() {
                $.ajax({
                    url: page + ".php",
                    method: "GET",
                    success: function(data) {
                        $("#main-content").html(data).fadeIn(200);
                        $(".topbar h4").text(page.charAt(0).toUpperCase() + page.slice(1));
                        $("#sideNav, #overlay").removeClass("active"); // close sidebar
                    },
                    error: function() {
                        $("#main-content").html("<p class='text-danger'>Failed to load page.</p>").fadeIn(200);
                    }
                });
            });
        }

        // Footer buttons navigation
        $(document).on("click", ".footer a[data-page], .footer button[data-page]", function(e) {
            e.preventDefault();
            var page = $(this).data("page");
            if (page) loadPage(page);
        });

        // Back button
        $("#btn-back").click(function() {
            window.history.back();
        });

        // Sidebar navigation links
        $(document).on("click", "#sideNav ul li a", function(e) {
            e.preventDefault();
            var page = $(this).data("page");
            if (page) loadPage(page);
        });

        // Sidebar toggle for mobile
        $("#menuBtn").click(function() {
            $("#sideNav, #overlay").toggleClass("active");
        });

        $("#overlay").click(function() {
            $("#sideNav, #overlay").removeClass("active");
        });

    });
</script>

<style>
    /* Sidebar and overlay styles */
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
</style>
</body>

</html>