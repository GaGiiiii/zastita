<?php include_once 'includes/header.php' ?>

<div class="container mt-5">
  <div class="row">
    <div class="col-6 offset-3">
      <div class="card" style="width: 18rem; margin: 0 auto;">
        <img src="<?php echo BASE_URL . "public/profile_images/" . $_SESSION['user']['user.image']; ?>" class="card-img-top" alt="Profile image">
        <div class="card-body">
          <h5 class="card-title">Username: <?php echo $_SESSION['user']['user.username']; ?></h5>
          <p>Email: <?php echo $_SESSION['user']['user.email']; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>



<?php include_once 'includes/footer.php' ?>