    </div> <!-- End of SPA content container -->

    <!-- jQuery and Bootstrap JS-->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="./js/jquery-3.7.0.min.js"></script>
    <script>
        AOS.init();

        // SPA navigation
        $(document).ready(function() {
            $('a.spa-link').click(function(e) {
                e.preventDefault();
                const url = $(this).attr('href');

                // Load page content via AJAX
                $('#spa-content').fadeOut(200, function() {
                    $('#spa-content').load(url + ' #spa-content > *', function() {
                        $('#spa-content').fadeIn(200);
                        AOS.refresh(); // refresh animations
                    });
                });

                // Update browser URL without reloading
                history.pushState(null, '', url);
            });

            // Handle browser back/forward buttons
            window.onpopstate = function() {
                $('#spa-content').load(location.pathname + ' #spa-content > *', function() {
                    AOS.refresh();
                });
            };
        });
    </script>
    </body>

    </html>