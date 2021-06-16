<?php

include_once 'includes/functions.php';
include_once 'includes/config.php';
require_once 'classes/Database.php';

if (Database::getInstance()->isUserLoggedIn()) {
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

if (isset($_POST['login'])) {
  $email = clean($_POST['email']);
  $password = clean($_POST['password']);

  $errors = array();

  if (empty($email)) {
    $errors['email'] = '<div class="mb-0 invalid-feedback">Please enter email.</div>';
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = '<div class="mb-0 invalid-feedback">Wrong email format.</div>';
    }
  }

  if (empty($password)) {
    $errors['password'] = '<div class="invalid-feedback">Please enter password.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "email" => $email,
      "password" => $password,
    );

    if (Database::getInstance()->loginUser($data)) {
      $_SESSION['login_success_message'] = '<div class="container-fluid">
      <div class="row">
        <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
          <div class="alert alert-warning alert-dismissible show mb-5" role="alert">
            <strong>Login successful! <i class="fas fa-check"></i></strong> Welcome ' . $_SESSION['user']['user.username'] .
        '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></i></button>
          </div>
        </div>
      </div>
    </div>';

      header("Location: " . BASE_URL);

      exit;
    } else {
      $errors['wrong_combination'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
      <strong>Danger!</strong> Wrong combination.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></i></button>
    </div>';
    }
  }
}
?>

<?php include 'includes/header.php'; ?>


<!-- -------- LOGIN ---------- -->
<section id="login" class="login-form mt-4">

  <div class="container">
    <div class="row justify-content-center">

      <div class="col-lg-6 col-md-8 col-sm-10 col-10 g-0">

        <?php echo $errors['wrong_combination'] ?? ""; ?>

        <div class="login-form-container">
          <h1>Login</h1>
          <p class="mb-4">Fields marked with <strong class="text-danger">*</strong> are required.</p>

          <form id="login-form" method="POST">
            <div class="mb-3">
              <label class="form-label">Email <strong class="text-danger">*</strong></label>
              <input id="email" name="email" type="email" class="form-control <?php if (isset($errors['email'])) echo 'is-invalid';
                                                                              else if (isset($email)) echo 'is-valid'; ?>" placeholder="Enter email" value="<?php echo $email ?? ""; ?>">
              <?php echo $errors['email'] ?? ""; ?>
            </div>
            <div class="mb-3">
              <label class="form-label">Password <strong class="text-danger">*</strong></label>
              <input id="password" name="password" type="password" class="form-control <?php if (isset($errors['password'])) echo 'is-invalid'; ?>" placeholder="Enter password">
              <?php echo $errors['password'] ?? ""; ?>
            </div>

            <button name="login" id="login-btn" type="submit" class="btn btn-primary w-100">Login</button>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- -------- LOGIN ---------- -->

<?php include 'includes/footer.php'; ?>