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
                                    <h4 class="fw-semibold">Submit Report</h4>
                                    <p class="text-muted font-size-sm">Assist with personal care</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="alert alert-danger" role="alert">
                                It is mandatory to inform us whether this task or medication was successful. Kindly use the checkbox below to indicate your response.
                            </div>
                            <hr>
                            <form action="./activities.php" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <input type="checkbox" value="Yes" class="form-check-input" id="yesOption" checked>
                                    <label for="yesOption" class="form-label">Yes / Successful</label>
                                </div>
                                <div id="yesoptiondisplay" class="mb-3">
                                    <label for="yesOptionNote" class="form-label">Write note</label>
                                    <textarea style="resize: none; border:1px solid #273c75;" class="form-control" name="txtYesNote" rows="3" placeholder="Write note"></textarea>
                                </div>

                                <div class="mb-3">
                                    <input type="checkbox" value="No" class="form-check-input" id="noOption">
                                    <label for="noOption" class="form-label">No / Not Successful</label>
                                </div>
                                <div id="nooptiondisplay" class="mb-3" style="display: none;">
                                    <label for="noOptionNote" class="form-label">Write note</label>
                                    <textarea style="resize: none; border:1px solid #273c75;" class="form-control" name="txtNoNote" rows="3" placeholder="Write note"></textarea>
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