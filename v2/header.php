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
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Geosoft Care">
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

    <link rel="stylesheet" href="./css/style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="./images/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="./images/favicon.png">
</head>

<body>
    <!-- SPA main content area -->
    <div id="main-content">

        <!-- SideNav -->
        <div id="sideNav">
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Profile">
                <p class="name">Samson Gift</p>
                <p class="email">samsonosaretin@yahoo.com</p>
                <p class="phone">07448222483</p>
                <button class="btn btn-outline-danger logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </div>
            <ul class="list-unstyled">
                <li><a href="./home" data-page="home"><i class="bi bi-house me-2"></i> Dashboard</a></li>
                <li><a href="./visit-logs" data-page="visit-logs"><i class="bi bi-calendar-event me-2"></i> Visits</a></li>
                <li><a href="./timesheet" data-page="timesheet"><i class="bi bi-book me-2"></i> Timesheet</a></li>
                <li><a href="./settings" data-page="settings"><i class="bi bi-gear me-2"></i> Settings</a></li>
            </ul>
        </div>

        <!-- Overlay -->
        <div id="overlay"></div>

        <!-- Topbar -->
        <div class="topbar">
            <button class="menu-btn fs-1" id="menuBtn"><i class="bi bi-list"></i></button>
            <h4>Dashboard</h4>
            <div class="d-flex align-items-center gap-3">
                <span id="topClock"></span>
                <i class="bi bi-bell-fill fs-5" title="Notifications"></i>
                <button class="btn btn-light" id="darkModeBtn"><i class="bi bi-moon"></i></button>
            </div>
        </div>