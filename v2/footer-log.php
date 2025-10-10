<!-- jQuery and Bootstrap JS-->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>
<!-- <script src="./script.js"></script>-->
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
    // Force zoom reset if user tries to pinch zoom
    document.addEventListener("gesturestart", function(e) {
        e.preventDefault();
        document.querySelector("meta[name=viewport]").setAttribute(
            "content",
            "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"
        );
    });
</script>
</body>

</html>