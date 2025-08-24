<?php require_once('header.php'); ?>
<!-- Features Section -->
<div class="main-wrapper">
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-left text-left">
                                <div class="mt-3">
                                    <h3 class="fw-bold">Duru Artrick</h3>
                                    <hr>
                                    <h4 class="fw-semibold">Check Out</h4>
                                    <div class="alert alert-danger" role="alert">
                                        Once you have confirmed that all tasks and medications are completed, please use the button below to check out of this visit/call
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="#" class="text-decoration-none text-black block bg-white">Time In &nbsp; &nbsp; &nbsp; <i class="bi bi-clock"></i> 08:00am</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a id="clientAssessment" href="./activities.php" class="text-decoration-none text-black block bg-white">Go to tasks</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card mb-3 bg-success">
                        <div class="card-body">
                            <a id="clientAssessment" href="./dashboard.php" class="text-decoration-none text-white block bg-success">Check Out &nbsp; &nbsp; &nbsp; <i class="bi bi-clock"></i> 09:00am</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<a href="./activities.php" style="position: fixed; top:200px; right:15px;" class="btn btn-success">Start</a>

<?php require_once('footer.php'); ?>