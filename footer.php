 </div>
 </main>
 <footer style="position: absolute; bottom:0; left:0; right:0; background-color:#2980b9; color:white;" class="footer pt-3">
     <div class="container-fluid">
         <div class="row align-items-center justify-content-lg-between">
             <div class="col-md-3 mb-4 text-center">Back</div>
         </div>
     </div>
 </footer>


 <script src="./assets/js/core/popper.min.js"></script>
 <script src="./assets/js/core/bootstrap.min.js"></script>
 <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
 <script src="script.js"></script>
 <script>
     var win = navigator.platform.indexOf('Win') > -1;
     if (win && document.querySelector('#sidenav-scrollbar')) {
         var options = {
             damping: '0.5'
         }
         Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
     }
 </script>
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 <script src="./assets/js/material-dashboard.min.js?v=3.0.0"></script>
 </body>

 </html>