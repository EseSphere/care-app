<?php require_once('dbconnection.php'); ?>
<!DOCTYPE html>
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
    <meta name="mobile-web-app-capable" content="yes">
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
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="./images/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="./images/favicon.png">
    <link rel="manifest" href="./manifest.json">
</head>

<body>
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-8">
                    <img src="./images/logo.png" id="gsLogo" class="img-fluid" alt="">
                </div>
                <div class="col-4 flex justify-end items-end text-end">
                    <a href="./signup.php" class="text-decoration-none fw-semibold text-white flex justify-end items-end text-end">New Pin?</a>
                </div>
            </div>
            <div class="d-flex justify-content-center text-white align-items-center h-100">
                <h3 class="mt-5">Authentication</h3>
            </div>
        </div>
    </div>