</div>
<div class="mt-3 p-5"></div>
<footer class="text-white text-center py-3 mt-5" style="position: fixed; background-color:rgba(39, 60, 117,1.0); bottom: 0; width: 100%;">
  <div class="row text-decoration-none text-center">
    <div class="col-md-3 col-3 text-center">
      <i class="bi bi-arrow-left-circle-fill text-white"></i>
    </div>
    <div class="col-md-3 col-3">
      <i class="bi bi-house-fill text-white"></i>
    </div>
    <div class="col-md-3 col-3">
      <i class="bi bi-person-dash-fill text-white"></i>
    </div>
    <div class="col-md-3 col-3">
      <i class="bi bi-clipboard-check text-white"></i>
    </div>
  </div>
</footer>

<script src="./js/jquery-3.7.0.min.js"></script>
<script src="./js/bootstrap.bundle.min.js"></script>

<script>
  // Checkbox logic
  const yesCheckbox = document.getElementById('yesOption');
  const noCheckbox = document.getElementById('noOption');
  const yesDisplay = document.getElementById('yesoptiondisplay');
  const noDisplay = document.getElementById('nooptiondisplay');

  if (yesDisplay && noDisplay) {
    yesDisplay.style.display = 'block';
    noDisplay.style.display = 'none';

    yesCheckbox?.addEventListener('change', () => {
      if (yesCheckbox.checked) {
        noCheckbox.checked = false;
        yesDisplay.style.display = 'block';
        noDisplay.style.display = 'none';
      } else yesDisplay.style.display = 'none';
    });

    noCheckbox?.addEventListener('change', () => {
      if (noCheckbox.checked) {
        yesCheckbox.checked = false;
        noDisplay.style.display = 'block';
        yesDisplay.style.display = 'none';
      } else noDisplay.style.display = 'none';
    });
  }

  // SPA navigation
  $(document).ready(function() {

    $('a.nav-link').click(function(e) {
      const href = $(this).attr('href');
      if (href && href.endsWith('.php')) {
        e.preventDefault();

        $('#content').fadeOut(100, function() {
          $('#content').load(href + ' #content > *', function(response, status, xhr) {
            if (status == 'error') {
              $('#content').html('<p>Error loading page.</p>');
            }
            $('#content').fadeIn(200);
          });
        });

        history.pushState(null, '', href);
      }
    });

    window.onpopstate = function() {
      const path = location.pathname.split('/').pop();
      $('#content').load(path + ' #content > *');
    };

  });
</script>

</body>

</html>