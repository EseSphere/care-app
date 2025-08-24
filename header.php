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
  </head>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: rgba(220, 221, 225, 1.0);
      font-size: 20px;
    }
  </style>
</head>

<body">

  <!-- Navbar -->
  <nav style="background-color: #3498db; color:white;" class="navbar text-white navbar-expand-lg fixed-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-white" href="index.php">Geosoft</a>
      <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <i class="bi bi-person-fill text-white"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item mt-3"></li>
          <li class="nav-item"><a class="nav-link text-white fw-bold <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {
                                                                        echo 'active';
                                                                      } ?>" href="index.php"><i class="bi bi-house"></i> Dashboard</a></li>
          <li id="userDetails" class="nav-item text-white px-3">
            <hr>
            <h4>Personal Details</h4>
            <p>Full Name <br><span>Samson Osaretin</span></p>
            <p>Phone <br><span>07448222483</span></p>
            <p>Email <br><span>samsonosaretin@yahoo.com</span></p>
            <hr>
          </li>
          <li class="nav-item"><a class="nav-link text-white fw-bold <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php') {
                                                                        echo 'active';
                                                                      } ?>" href="contact.php"><i class="bi bi-envelope"></i> Timesheet</a>
          </li>
          <li class="nav-item"><a class="nav-link text-white fw-bold <?php if (basename($_SERVER['PHP_SELF']) == 'logout.php') {
                                                                        echo 'active';
                                                                      } ?>" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
          </li>
          <li class="nav-item mt-3"></li>
        </ul>
      </div>
    </div>
  </nav>
  <div id="mid-panel" class="w-100 p-2 text-white">
    <div class="row text-decoration-none mt-2">
      <div class="col-md-5 col-5">
        <input style="background-color: inherit; border:none; color:white; font-weight:600;" type="date" class="form-control" value="<?= date('Y-m-d'); ?>">
      </div>
      <div class="col-md-7 col-7 text-end">
        <h5 class="fw-semibold mt-2"><i class="bi bi-clock"></i> 8h 30m</h5>
      </div>
      <div class="col-md-12 col-12">
        <div style="overflow-x: auto;">
          <div class="scroll-container">
            <table class="table table-condensed text-center text-white no-borders">
              <tr>
                <td>01</td>
                <td>02</td>
                <td>03</td>
                <td>04</td>
                <td>05</td>
                <td>06</td>
                <td>07</td>
                <td>08</td>
                <td>09</td>
                <td>10</td>
                <td>11</td>
              </tr>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>