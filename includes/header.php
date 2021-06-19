<?php require_once "classes/Database.php"; ?>
<?php require_once "includes/config.php"; ?>
<?php include_once "includes/functions.php"; ?>
<?php
$currentPage = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], '/')); //  /prijava /registracija /index.php
// echo $currentPage;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ShopZZy</title>
  <!-- BOOTSTRAP -->
  <link href="https://bootswatch.com/5/sandstone/bootstrap.min.css" rel="stylesheet">
  <!-- CUSTOM CSS -->
  <link rel="stylesheet" href="<?php echo BASE_URL . "public/css/style.css" ?>"> <!-- Ovde treba putanja kao da smo u index.php fajlu a ne u header.php -->
  <!-- FONT AWESOME -->
  <script src="https://kit.fontawesome.com/5c5689b7a2.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
</head>

<body>

  <button id="scroll-to-top" style="display: none" class="btn btn-primary"><i class="fas fa-angle-double-up"></i></button>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">ShopZZy</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL; ?>cart.php">Cart</a>
          </li>
        </ul>
        <ul class="navbar-nav mb-2 mb-lg-0">
          <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Logged in as: <?php echo $_SESSION['user']['user.username']; ?>
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>profile.php">Profile</a></li>
                <?php if ($_SESSION['user']['user.is_admin']) { ?>
                  <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>admin.php">Admin</a></li>
                <?php } ?>
                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout.php">Logout</a></li>
              </ul>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo BASE_URL; ?>register.php">Register</a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
  <!-- NAVBAR -->