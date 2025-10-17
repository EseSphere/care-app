<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Geosoft Care is an app designed for care settings to manage schedules, staff, and care plans efficiently.">
    <meta name="keywords" content="Care App, Geosoft Care, Staff Management, Schedule, Healthcare App">
    <meta name="author" content="Geosoft Care">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0d6efd"> <!-- Matches Bootstrap primary blue -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Geosoft Care">
    <meta name="mobile-web-app-capable" content="yes">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Geosoft Care | App for care settings">
    <meta property="og:description" content="Geosoft Care is an app designed for care settings to manage schedules, staff, and care plans efficiently.">
    <meta property="og:image" content="./images/favicon.png">
    <meta property="og:url" content="https://app.geosoftcare.co.uk">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Geosoft Care | App for care settings">
    <meta name="twitter:description" content="Geosoft Care is an app designed for care settings to manage schedules, staff, and care plans efficiently.">
    <meta name="twitter:image" content="./images/favicon.png">
    <title>Geosoft Care | App for care settings</title>
    <link rel="stylesheet" href="./css/style1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="./images/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="./images/favicon.png">
</head>

<body>

    <div id="sideNav">
        <div class="user-info">
            <img src="./images/avatar.webp" alt="Profile">
            <p class="name fs-6 fw-bold text-dark">Samson Gift</p>
            <p class="email text-dark">samsonosaretin@yahoo.com</p>
            <p class="phone text-dark">07448222483</p>
        </div>
        <hr>
        <ul class="list-unstyled">
            <li><a href="./home.php" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'home.php') {
                                                            echo 'active';
                                                        } ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
            <li><a href="./visit-logs.php" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'visit-logs.php') {
                                                                echo 'active';
                                                            } ?>"><i class="bi bi-calendar-event me-2"></i> Visits</a></li>
            <li><a href="./timesheet.php" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'timesheet.php') {
                                                                echo 'active';
                                                            } ?>"><i class="bi bi-book me-2"></i> Timesheet</a></li>
            <li><a href="./calculator.php" class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'timesheet.php') {
                                                                echo 'active';
                                                            } ?>"><i class="bi bi-calculator me-2"></i> Calculator</a></li>
        </ul>
        <hr>

        <div class="navbar-content bg-light p-2" style="font-family: Arial, sans-serif;">
            <span class="app-title" style="font-weight:bold; font-size:18px;">Geosoft</span>
            <span class="app-version" style="font-size:14px; margin-left:15px;">v3.0.6</span><br>
            <span class="app-status" style="font-size:15px; color:#555;">Mobile App | Beta Release</span><br>
            <span class="app-environment" style="font-size:15px; color:#777;">Environment: Development</span>
        </div>

        <a href="./logout" class="btn btn-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>