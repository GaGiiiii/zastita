<?php

require_once "./classes/Database.php";
require_once "./includes/functions.php";

if (isset($_POST['remove-from-cart'])) {
  $productID = $_POST['productID'];
  $product = Database::getInstance()->getProduct($productID);
  array_splice($_SESSION['cart'], array_search($product, $_SESSION['cart']), 1);
}

?>

<?php include_once 'includes/header.php' ?>

<?php printFormatedFlashMessage("add_product_success_message"); ?>
<?php printFormatedFlashMessage("delete_product_success_message"); ?>
<?php printFormatedFlashMessage("update_product_success_message"); ?>

<div class="container mt-5">
  <div class="row">
    <h1 class="g-0 mb-1">Items in your cart</h1>
    <p class="g-0">
      Total price in cart: <?php echo totalPriceInCart() ?? 0; ?> RSD
      <hr>
    </p>
    <table class="table table-product table-hover table-striped">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th scope="col">Image</th>
          <th scope="col">Price</th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['cart'] as $index => $item) : ?>
          <tr>
            <th scope="row" style="vertical-align: middle;"><?php echo ++$index; ?>.</th>
            <td><?php echo $item['product.name']; ?></td>
            <td><?php echo $item['product.description']; ?></td>
            <td><img class="img-thumbnail" src="<?php echo $item['product.image']; ?>" alt="<?php echo $item['product.name']; ?> image" width="75px" height="75px"></td>
            <td><?php echo $item['product.price']; ?></td>
            <td>
              <button type="button" class="btn btn-danger add-product-btn" data-bs-toggle="modal" data-bs-target="#removeFromCartModal<?php echo $item['product.id']; ?>">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>

          <!-- Delete Product Modal -->
          <div class="modal fade" id="removeFromCartModal<?php echo $item['product.id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-danger fw-bold" id="exampleModalLabel">DANGER!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure that you want to remove product <strong>"<?php echo $item['product.name']; ?>"</strong> from cart?
                </div>
                <div class="modal-footer">
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="productID" value="<?php echo $item['product.id'] ?? "-1"; ?>">
                    <button type="submit" name="remove-from-cart" class="btn btn-danger">YES</button>
                  </form>
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>
</div>

<?php include_once 'includes/footer.php' ?>