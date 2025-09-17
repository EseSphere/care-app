<?php require_once('dbconnection.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Geosoft Care - Modern healthcare management web application for care services.">
        <meta name="keywords" content="Geosoft Care, healthcare, care management, patient care, web app">
        <meta name="author" content="Geosoft">
        <meta name="theme-color" content="#0d6efd">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="mobile-web-app-capable" content="yes">
        <meta property="og:title" content="Geosoft Care">
        <meta property="og:description" content="A modern healthcare management web application for care services.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://app.geosoftcare.co.uk">
        <meta property="og:image" content="./images/favicon.png">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="Geosoft Care">
        <meta name="twitter:description" content="A modern healthcare management web application for care services.">
        <meta name="twitter:image" content="./images/favicon.png">
        <link rel="icon" href="./images/favicon.png" type="image/x-icon">
        <link rel="apple-touch-icon" href="./images/favicon.png">
        <title><?= $appTitle ?></title>
        <link href="./css/style.css" rel="stylesheet">
        <link href="./css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
            font-size: 20px;
        }
    </style>
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