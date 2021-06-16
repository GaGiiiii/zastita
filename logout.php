<?php

require_once 'classes/Database.php';
require_once 'includes/config.php';

if (!Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['unauthorized_access'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-danger alert-dismissible show" role="alert">
        <strong>Danger!</strong> Access denied! <i class="fas fa-exclamation-triangle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>';
  header("Location: " . BASE_URL);

  exit;
}

$leavingUN = $_SESSION['user']['user.username'];

Database::getInstance()->logoutUser();
$_SESSION['logout_success_message'] = '<div class="container-fluid">
  <div class="row">
    <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
      <div class="alert alert-warning alert-dismissible show" role="alert">
        <strong>Odjava uspešna! <i class="fas fa-check"></i></strong> See you soon ' . $leavingUN .
  '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    </div>
  </div>
</div>';
header("Location: " . BASE_URL);

exit;
