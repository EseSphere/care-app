<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Geosoft Care</title>
  <link type="text/css" href="./css/style.css" rel="stylesheet">
  <link type="text/css" href="./css/bootstrap.min.css" rel="stylesheet">
  <link type="text/css" href="./css/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">OfflineSite</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') {
                                                    echo 'active';
                                                  } ?>" href="index.php"><i class="bi bi-house"></i> Home</a></li>
          <li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') {
                                                    echo 'active';
                                                  } ?>" href="about.php"><i class="bi bi-info-circle"></i> About</a></li>
          <li class="nav-item"><a class="nav-link <?php if (basename($_SERVER['PHP_SELF']) == 'contact.php') {
                                                    echo 'active';
                                                  } ?>" href="contact.php"><i class="bi bi-envelope"></i> Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>