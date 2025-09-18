</div>
<div class="footer">
    <button onclick="history.back()" title="Back" id="btn-back"><i class="bi bi-arrow-left"></i></button>
    <a href="./home" title="Home" data-page="dashboard"><i class="bi bi-house"></i></a>
    <a href="./logs" title="Log" data-page="reports"><i class="bi bi-journal-text"></i></a>
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
                    },
                    error: function() {
                        $("#main-content").html("<p class='text-danger'>Failed to load page.</p>").fadeIn(200);
                    }
                });
            });
        }

        // Footer buttons navigation
        $(".footer button[data-page]").click(function() {
            var page = $(this).data("page");
            loadPage(page);
        });

        // Back button
        $("#btn-back").click(function() {
            window.history.back();
        });

        // Sidebar navigation links
        $("#sideNav ul li a").click(function(e) {
            e.preventDefault();
            var page = $(this).data("page");
            loadPage(page);
        });
    });
</script>
</body>

</html>