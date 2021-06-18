<?php

require_once "./classes/Database.php";

$products = Database::getInstance()->getAllProducts();

?>

<?php include_once 'includes/header.php' ?>

<?php printFormatedFlashMessage("unauthorized_access"); ?>
<?php printFormatedFlashMessage("logout_success_message"); ?>
<?php printFormatedFlashMessage("login_success_message"); ?>
<?php printFormatedFlashMessage("register_success_message"); ?>

<div class="container mt-5">
  <div class="row">
    <h1 class="mb-2 d-inline-block">Products
      <hr>
    </h1>

    <?php foreach ($products as $product) : ?>
      <div class="col-lg-4 col-md-6 card-col">
        <div class="card">
          <img src="<?php echo $product['product.image']; ?>" class="card-img-top product-image" alt="Product image" height="275px">
          <div class="card-body">
            <h5 class="card-title"><?php echo $product['product.name']; ?></h5>
            <p class="card-text mb-0"><?php echo $product['product.description']; ?></p>
            <p class="card-text mt-1"><?php echo $product['product.price']; ?> RSD</p>
          </div>
          <div class="card-body pt-0">
            <a href="#" class="card-link">Add to cart</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>


<?php include_once 'includes/footer.php' ?>