<?php

require_once "./classes/Database.php";
require_once "./includes/functions.php";

$products = Database::getInstance()->getAllProducts();

if (!isset($_SESSION['cart']) && Database::getInstance()->isUserLoggedIn()) {
  $_SESSION['cart'] = array();
}

if (isset($_POST['add-to-cart'])) {
  $productID = $_POST['productID'];
  $product = Database::getInstance()->getProduct($productID);
  if (!inCart($product)) {
    array_push($_SESSION['cart'], $product);
  }
}

if (isset($_POST['remove-from-cart'])) {
  $productID = $_POST['productID'];
  $product = Database::getInstance()->getProduct($productID);
  array_splice($_SESSION['cart'], array_search($product, $_SESSION['cart']), 1);
}

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
          <img src="<?php echo $product['product.image']; ?>" class="card-img-top product-image img-thumbnail" alt="Product image" height="275px">
          <div class="card-body">
            <h5 class="card-title"><?php echo $product['product.name']; ?></h5>
            <p class="card-text mb-0"><?php echo $product['product.description']; ?></p>
            <p class="card-text mt-1"><?php echo $product['product.price']; ?> RSD</p>
          </div>
          <div class="card-body pt-0">
            <?php if (Database::getInstance()->isUserLoggedIn()) { ?>
              <form method="POST" class="d-inline">
                <input type="hidden" name="productID" value="<?php echo $product['product.id'] ?? "-1"; ?>">
                <button type="submit" name="<?php echo !inCart($product) ? "add-to-cart" : "remove-from-cart"; ?>" class="btn btn-primary"><?php echo !inCart($product) ? "Add to cart" : "Remove from cart"; ?></button>
              </form>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

  </div>
</div>


<?php include_once 'includes/footer.php' ?>