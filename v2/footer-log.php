<!-- jQuery and Bootstrap JS-->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="./js/jquery-3.7.0.min.js"></script>
<!--<script src="./script.js"></script> -->
<script>
    AOS.init();
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