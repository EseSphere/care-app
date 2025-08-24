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
                                    <h4 class="fw-semibold">General observation</h4>
                                    <div class="alert alert-danger" role="alert">
                                        It is mandatory to provide your general observations about Duru Artrick, as this will help us to better understand his health status and condition.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="POST" action="./check-out.php" enctype="multipart/form-data" autocomplete="off">
                                <div class="mb-3">
                                    <label for="yesOptionNote" class="form-label">What's your observation?</label>
                                    <textarea style="resize: none; border:1px solid #273c75;" class="form-control" name="txtYesNote" rows="4" placeholder="Write note" required></textarea>
                                </div>
                                <div class="mt-4">
                                    <button style="height: 50px;" type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<a href="./activities.php" style="position: fixed; top:200px; right:15px;" class="btn btn-success">Start</a>

<?php require_once('footer.php'); ?>