<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Ongoing Visit</title>
</head>
<!-- Bootstrap Modal -->
<div class="d-flex justify-content-center align-items-center min-vh-100 bg-dark">
    <div class="container">
        <div class="modal-content shadow-lg border-0 rounded-3" id="ongoingCallModal">
            <div class="modal-header bg-danger text-white rounded-top-3">
                <h5 class="modal-title fw-bold" id="ongoingCallLabel">
                    ⚠ Ongoing Call Detected
                </h5>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-3 fs-6">
                    You have a visit that is still <strong>in-progress</strong>.<br>
                    Please click the continue button to complete the previous call before starting a new one.
                </p>
            </div>
            <div class="modal-footer d-flex justify-content-center gap-2 py-3">
                <a style="height: 45px;" href="#" id="continueCallBtn" class="btn btn-primary w-md-auto">Continue</a>
                <button onclick="window.history.go(-2);" style="height: 45px;" type="button" id="startNewShiftAnyway" class="btn btn-outline-secondary w-md-auto">Go Back</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script>
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const clientId = urlParams.get('uryyToeSS4');
    const clientshift_date = urlParams.get('Clientshift_Date'); // Updated
    const careCall = urlParams.get('care_calls');
    const userId = urlParams.get('userId');
    const carerId = urlParams.get('carerId');

    const continueBtn = document.getElementById('continueCallBtn');

    continueBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const url = `activities.php?uryyToeSS4=${clientId}&Clientshift_Date=${clientshift_date}&care_calls=${careCall}&userId=${userId}&carerId=${carerId}`;
        window.location.href = url;
    });
</script>
</body>

</html>