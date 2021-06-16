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

if (isset($_POST['register'])) {
  $email = clean($_POST['email']);
  $password = clean($_POST['password']);
  $password2 = clean($_POST['password2']);
  $username = clean($_POST['username']);

  $errors = array();

  if (empty($email)) {
    $errors['email'] = '<div class="mb-0 invalid-feedback">Please enter email.</div>';
  } else {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = '<div class="mb-0 invalid-feedback">Wrong email format.</div>';
    }
  }

  if (empty($password)) {
    $errors['password'] = '<div class="mb-0 invalid-feedback">Please enter password.</div>';
  } else {
    if ($password != $password2) {
      $errors['password_confirm'] = '<div class="mb-0 invalid-feedback">Passwords do not match.</div>';
    } else {
      if (empty($password2)) {
        $errors['password2'] = '<div class="mb-0 invalid-feedback">Please confirm password.</div>';
      }
    }
  }

  if (empty($username)) {
    $errors['username'] = '<div class="mb-0 invalid-feedback">Please enter username.</div>';
  }

  if (count($errors) == 0) {

    // FORA KOD UPLOADOVANJA FAJLOVA JE TA STO JE ON UPLOADOVAN NA TEMP LOKACIJI I ONDA NAKON SVIH PROVERA MORAMO DA POMERIMO FAJL SA TEMP LOKACIJE I TEMP IMENOM 
    // NA PRAVU LOKACIJU I SA PRAVIM IMENOM. PRAVU LOKACIJU MORAMO PREKO __DIR__ ILI NECEGA SLICNOG, NE MOZE PREKO HTTP://URL/PUBLIC....

    if (!empty($_FILES['file']['name'])) { // Da li je poslao neke fajlove
      $file = $_FILES['file'];

      $file_name = $file['name'];
      $file_tmp = $file['tmp_name'];
      $file_size = $file['size'];
      $file_error = $file['error'];

      $file_ext = explode('.', $file_name);
      $file_ext = strtolower(end($file_ext));

      $file_name_new = $username . date("Ymd") . "." . $file_ext;
      $file_destination = dirname(__FILE__) . '/public/profile_images/' . $file_name_new;

      $uploadOk = 1;

      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
      } else {
        if (move_uploaded_file($file_tmp, $file_destination)) {
          echo "The file " . htmlspecialchars(basename($_FILES["upload"]["name"])) . " has been uploaded.";
          $image = $file_name_new;
        } else {
          echo "Sorry, there was an error uploading your file.";
        }
      }
    } else {
      $image = "noimage.png";
    }

    $data = array(
      "email" => $email,
      "password" => $password,
      "username" => $username,
      "image" => $image,
    );

    if (Database::getInstance()->takenEmail($email)) {
      $errors['taken_email'] = '<div class="alert alert-danger alert-dismissible show" role="alert">
      <strong>Greška!</strong> Email taken.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    } else if (Database::getInstance()->registerUser($data)) {
      $_SESSION['register_success_message'] = '<div class="container-fluid">
        <div class="row">
          <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
            <div class="alert alert-warning alert-dismissible show" role="alert" style="margin-bottom: 4rem;">
              <strong>Registration successful! <i class="fas fa-check"></i></strong> Welcome ' . $_SESSION['user']['user.username'] .
          '. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        </div>
      </div>';

      header("Location: " . BASE_URL);

      exit;
    }
  }
}
?>

<?php include 'includes/header.php'; ?>

<!-- -------- REGISTRACIJA ---------- -->
<section id="register" class="registracija mt-4 mb-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8 col-sm-10 col-10 g-0">

        <div class="login-form-container">

          <h1 class="mb-1">Register</h1>
          <p class="mb-4">Fields marked with <strong class="text-danger">*</strong> are required.</p>

          <?php echo $errors['taken_email'] ?? ""; ?>

          <form enctype="multipart/form-data" method="POST">

            <div class="mb-4">
              <label class="form-label">Username <strong class="text-danger">*</strong></label>
              <input name="username" type="text" class="form-control <?php if (isset($errors['username'])) echo 'is-invalid';
                                                                      else if (isset($username)) echo 'is-valid'; ?>" placeholder="Enter username" value="<?php echo $username ?? ""; ?>">
              <?php echo $errors['username'] ?? ""; ?>
            </div>

            <div class="mb-4">
              <label class="form-label">Email <strong class="text-danger">*</strong></label>
              <input name="email" type="email" class="form-control <?php if (isset($errors['email']) || isset($errors['taken_email'])) echo 'is-invalid';
                                                                    else if (isset($email)) echo 'is-valid'; ?>" placeholder="Enter email" value="<?php echo $email ?? ""; ?>">
              <?php echo $errors['email'] ?? ""; ?>
            </div>

            <div class="mb-4">
              <label class="form-label">Password <strong class="text-danger">*</strong></label>
              <input name="password" type="password" class="form-control <?php if (isset($errors['password']) || isset($errors['password_confirm'])) echo 'is-invalid'; ?>" placeholder="Enter password">
              <?php echo $errors['password'] ?? ""; ?>
              <?php echo $errors['password_confirm'] ?? ""; ?>
            </div>

            <div class="mb-4">
              <label class="form-label">Confirm password <strong class="text-danger">*</strong></label>
              <input name="password2" type="password" class="form-control <?php if (isset($errors['password2'])) echo 'is-invalid'; ?>" placeholder="Confirm password">
              <?php echo $errors['password2'] ?? ""; ?>
            </div>

            <div class="mb-4">
              <label class="form-label">Upload image</label><br>
              <input type="file" name="file">
            </div>

            <button name="register" type="submit" class="btn btn-primary w-100">Register</button>
          </form>
        </div>

      </div>
    </div>
  </div>
</section>
<!-- -------- REGISTRACIJA ---------- -->

<?php include 'includes/footer.php'; ?>