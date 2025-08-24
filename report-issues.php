<?php require_once('header.php'); ?>
<!-- Features Section -->
<div class="main-wrapper">
    <div class="container">
        <div class="main-body">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <i style="font-size: 120px; color:#40739e;" class="bi bi-person-fill"></i>
                                <div class="mt-3">
                                    <h4>Duru Artrick</h4>
                                    <p class="fw-bold font-size-sm">REPORT ISSUES</p>
                                    <a href="./dnacpr.php" class="btn btn-success text-decoration-none">DNACPR</a>
                                    <a href="./allergies.php" class="btn btn-danger text-decoration-none">ALERGIES</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Please raise a concern as soon as possible. If you have noticed a change in condition, environment, unusual behavior, or health concern. Let us know once this has been reported. Thank you.</h5>
                            <hr>
                            <form>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Title</label>
                                    <input style="height: 50px;" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Write note</label>
                                    <textarea style="resize: none;" class="form-control" name="txtNote" rows="5" id="" placeholder="Write note"></textarea>
                                </div>
                                <button style="height: 50px;" type="submit" class="btn btn-primary">Submit</button>
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