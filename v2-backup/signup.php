<?php require_once('header-log.php'); ?>

<div class="mt-5" data-aos="zoom-in" data-aos-duration="1000" style="z-index:1000;">
    <div style="border: none; z-index:1000;" class="p-4 text-center">
        <h3 class="mb-3 fw-bold">Account Setup</h3>
        <div style="border-radius: 12px; border-left:7px solid rgba(64, 115, 158,1.0); padding:8px; margin-bottom:40px;" role="alert">
            Signing in on a new device? Please use the form below to set up your account.
        </div>
        <form action="./create-pin.php" method="post" enctype="multipart/form-data">
            <h6 class="mb-3 fw-semibold w-100 justify-start items-start text-start">Enter Email</h6>
            <input style="height: 50px;" type="email" id="email" class="form-control mb-4" placeholder="Enter your email" required>
            <div class="form-group w-100 justify-start items-start text-start">
                <button style="height: 45px;" class="btn btn-primary">Continue</button>
            </div>
        </form>
    </div>
</div>

<?php require_once('footer-log.php'); ?>