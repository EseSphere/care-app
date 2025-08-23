<?php require_once('dbconnections.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="Care Management & Digital Solutions Platform - Samson Osaretin (Ese Sphere)">
    <meta name="description" content="A modern care management and digital solutions platform developed by Samson Osaretin under Ese Sphere. Provides tools for creating, managing, and deploying innovative healthcare and business solutions with usability, scalability, and performance in mind.">
    <meta name="keywords" content="Care Management, Healthcare Solutions, Digital Platform, Business Solutions, Ese Sphere, Samson Osaretin, HealthTech, Scalable Applications">
    <meta name="author" content="Samson Osaretin, Ese Sphere">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://geosoftcare.co.uk/">
    <meta property="og:title" content="Care Management & Digital Solutions Platform">
    <meta property="og:description" content="Innovative healthcare and business solutions with usability, scalability, and performance. Developed by Samson Osaretin under Ese Sphere.">
    <meta property="og:image" content="./assets/img/preview.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://geosoftcare.co.uk/">
    <meta name="twitter:title" content="Care Management & Digital Solutions Platform">
    <meta name="twitter:description" content="Innovative healthcare and business solutions with usability, scalability, and performance. Developed by Samson Osaretin under Ese Sphere.">
    <meta name="twitter:image" content="./assets/img/preview.png">
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/favicon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <title><?= $appTitle ?></title>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <link href="./assets/css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link id="pagestyle" href="./assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
        <div style="height: 110px; border-bottom-left-radius:30px;" class="sidenav-header bg-white p-3">
            <img src="./assets/img/logo.png" alt="" class="img-fluid">
        </div>
        <hr class="horizontal light mt-5 mb-4">
        <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white active bg-gradient-primary" href="./dashboard.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-home text-white"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li style="margin-top:-32px;" class="nav-item p-2 m-2">
                    <a class="text-white text-decoration-none p-1 m-2" href="#">
                        <h3>Personal details</h3>
                        <p><span>Full Name</span><br><span>Samson Osaretin</span></p>
                        <hr>
                        <p><span>Phone</span><br><span>07448222483</span></p>
                        <p><span>Email</span><br><span>samsonosaretin@yahoo.com</span></p>
                    </a>
                </li>
                <li style="margin-top: -32px;" class="nav-item">
                    <a class="nav-link text-white active bg-gradient-primary" href="./timesheet.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <span class="nav-link-text ms-1">Timesheet</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidenav-footer position-absolute w-100 bottom-0 ">
            <div class="mx-3">
                <a class="btn bg-gradient-primary mt-4 w-100" href="./logout.php" type="button">Logout</a>
            </div>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100">
        <nav style="background-color: #3498db; width: 100%;" class="navbar navbar-main text-white navbar-expand-lg px-0 shadow-none" id="navbarBlur" navbar-scroll="true">
            <div style="background-color: #3498db; width: 100%; height:70px;" class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <h1 class="font-weight-bolder mb-0 text-light">Geosoft</h1>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a style="font-size:35px; color:white;" href="javascript:;" class="nav-link text-body p-2" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="fas fa-bars text-white"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <nav style="background-color:#2980b9; width: 100%;" class="navbar navbar-main text-white navbar-expand-lg px-0 shadow-none" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3 w-100">
                <div class="row text-decoration-none text-white w-100">
                    <div class="col-md-8 col-8 text-start">
                        <input type="date" class="form-control" name="txtDate" />
                    </div>
                    <div class="col-md-4 col-4 text-end">
                        <h4>0h 30m</h4>
                    </div>
                    <div class="col-md-12 col-12">
                        <hr>
                        <table style="background-color:inherit;" class="table table-condensed">
                            <tr>
                                <td id="tbl-visit-time">01</td>
                                <td id="tbl-visit-time">02</td>
                                <td id="tbl-visit-time">03</td>
                                <td id="tbl-visit-time">04</td>
                                <td id="tbl-visit-time">05</td>
                                <td id="tbl-visit-time">06</td>
                                <td id="tbl-visit-time">07</td>
                                <td id="tbl-visit-time">08</td>
                                <td id="tbl-visit-time">09</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </nav>