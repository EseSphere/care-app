<?php require_once('logs-header.php'); ?>
<section style="background-image:url(./images/23-bgs.jpg); background-size:cover; position: fixed; height: 350px; top: 0; left: 0; width: 100%; z-index: 0;">
    <div class="d-flex justify-content-center text-white align-items-center h-75">
        <h3>Setup Account</h3>
    </div>
</section>
<div style="margin-top: 220px; z-index:1;">
    <div style="border: none;" class="card p-4 text-center">
        <div class="alert alert-success" role="alert">
            Signing in on a new device? Please use the form below to set up your account.
        </div>
        <hr>
        <form action="./create-pin.php" method="post" enctype="multipart/form-data">
            <h3 class="mb-3 fw-bold">Enter Email</h3>
            <input style="height: 50px;" type="email" id="email" class="form-control mb-4" placeholder="Enter your email" required>

            <div class="d-grid gap-2">
                <button style="height: 50px;" class="btn btn-primary" onclick="proceed()">Proceed</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('logs-footer.php'); ?>