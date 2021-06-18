<?php

require_once "./classes/Database.php";
require_once "./includes/functions.php";

if (isset($_POST['delete-product'])) {
  $id = clean($_POST['product_id']);

  if (Database::getInstance()->deleteProduct($id)) {
    $_SESSION['delete_product_success_message'] = '<div class="container-fluid">
    <div class="row">
      <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          Success! <strong>"' . clean($_POST['product_name']) . '"</strong> successfully deleted! <i class="fas fa-check"></i>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>';
  }
}

if (isset($_POST['update-product'])) {
  $name_edit = clean($_POST['name_edit']);
  $description_edit = clean($_POST['description_edit']);
  $price_edit = clean($_POST['price_edit']);
  $image_edit = clean($_POST['image_edit']);
  $id_edit = clean($_POST['id_edit']);

  $errors = array();

  if (empty($name_edit)) {
    $errors['name_edit'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($description_edit)) {
    $errors['description_edit'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }

  if (empty($image_edit)) {
    $errors['image_edit'] = '<div class="mb-0 invalid-feedback">Please enter image URL.</div>';
  }

  if (empty($price_edit)) {
    $errors['price_edit'] = '<div class="mb-0 invalid-feedback">Please enter price.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "name_edit" => $name_edit,
      "description_edit" => $description_edit,
      "image_edit" => $image_edit,
      "price_edit" => $price_edit,
    );

    if (Database::getInstance()->updateProduct($data, $id_edit)) {
      $_SESSION['update_product_success_message'] = '<div class="container-fluid">
      <div class="row">
        <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Success! <strong>"' . $name_edit . '"</strong> successfully updated! <i class="fas fa-check"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </div>';
      $name_edit = "";
      $description_edit = "";
      $image_edit = "";
      $price_edit = "";
    }
  }
}

if (isset($_POST['add-product'])) {
  $name = clean($_POST['name']);
  $description = clean($_POST['description']);
  $image = clean($_POST['image']);
  $price = clean($_POST['price']);

  $errors = array();

  if (empty($name)) {
    $errors['name'] = '<div class="mb-0 invalid-feedback">Please enter name.</div>';
  }

  if (empty($description)) {
    $errors['description'] = '<div class="mb-0 invalid-feedback">Please enter description.</div>';
  }

  if (empty($price)) {
    $errors['price'] = '<div class="mb-0 invalid-feedback">Please enter price.</div>';
  }

  if (empty($image)) {
    $errors['image'] = '<div class="mb-0 invalid-feedback">Please enter image URL.</div>';
  }

  if (count($errors) == 0) {
    $data = array(
      "name" => $name,
      "description" => $description,
      "price" => $price,
      "image" => $image,
    );

    if (Database::getInstance()->addProduct($data)) {
      $_SESSION['add_product_success_message'] = '<div class="container-fluid">
      <div class="row">
        <div class="col-md-10 col-sm-10 offset-sm-1 offset-md-1 p-0 mt-5">
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Success!<strong> "' . $name . '"</strong> successfully added! <i class="fas fa-check"></i>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        </div>
      </div>
    </div>';

      $name = "";
      $description = "";
      $price = "";
      $image = "";
    }
  }
}

$products = Database::getInstance()->getAllProducts();

?>

<?php include_once 'includes/header.php' ?>

<?php printFormatedFlashMessage("add_product_success_message"); ?>
<?php printFormatedFlashMessage("delete_product_success_message"); ?>
<?php printFormatedFlashMessage("update_product_success_message"); ?>

<div class="container mt-5">
  <div class="row">
    <h1 class="g-0 mb-3">Admin Menu
      <hr>
    </h1>
    <table class="table table-product">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Name</th>
          <th scope="col">Description</th>
          <th scope="col">Image</th>
          <th scope="col">Price</th>
          <th scope="col">
            <button type="button" class="btn btn-primary add-product-btn" data-bs-toggle="modal" data-bs-target="#addProductModal">
              <i class="fas fa-plus"></i>
            </button>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $index => $product) : ?>
          <tr>
            <th scope="row" style="vertical-align: middle;"><?php echo ++$index; ?>.</th>
            <td><?php echo $product['product.name']; ?></td>
            <td><?php echo $product['product.description']; ?></td>
            <td><img src="<?php echo $product['product.image']; ?>" alt="<?php echo $product['product.name']; ?> image" width="75px" height="75px"></td>
            <td><?php echo $product['product.price']; ?></td>
            <td>
              <button type="button" class="btn btn-warning add-product-btn text-white" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['product.id']; ?>">
                <i class="fas fa-edit"></i>
              </button>
              &nbsp;
              <button type="button" class="btn btn-danger add-product-btn" data-bs-toggle="modal" data-bs-target="#deleteProductModal<?php echo $product['product.id']; ?>">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>



          <!-- Edit Product Modal -->
          <div class="modal fade" id="editProductModal<?php echo $product['product.id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit "<?php echo $product['product.name']; ?>"</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                  <form method="POST">

                    <div class="mb-4">
                      <label class="form-label">Name <strong class="text-danger">*</strong></label>
                      <input name="name_edit" type="text" class="form-control <?php if (isset($errors['name_edit'])) echo 'is-invalid';
                                                                              else if (isset($name_edit)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $product['product.name'] ?? ""; ?>">
                      <?php echo $errors['name_edit'] ?? ""; ?>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">Description <strong class="text-danger">*</strong></label>
                      <textarea name="description_edit" class="form-control <?php if (isset($errors['description_edit'])) echo 'is-invalid';
                                                                            else if (isset($description_edit)) echo 'is-valid'; ?>" placeholder="Enter description" id="floatingTextarea2" style="height: 100px"><?php echo $product['product.description'] ?? ""; ?></textarea>
                      <?php echo $errors['description_edit'] ?? ""; ?>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">Image <strong class="text-danger">*</strong></label>
                      <input name="image_edit" type="text" class="form-control <?php if (isset($errors['image_edit']) || isset($errors['taken_image_edit'])) echo 'is-invalid';
                                                                                else if (isset($image_edit)) echo 'is-valid'; ?>" placeholder="Enter image_edit URL" value="<?php echo $product['product.image'] ?? ""; ?>">
                      <?php echo $errors['image_edit'] ?? ""; ?>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">Price <strong class="text-danger">*</strong></label>
                      <input name="price_edit" type="number" class="form-control <?php if (isset($errors['price_edit']) || isset($errors['price_edit'])) echo 'is-invalid';
                                                                                  else if (isset($price_edit)) echo 'is-valid'; ?>" placeholder="Enter price_edit" value="<?php echo $product['product.price'] ?? 0; ?>">
                      <?php echo $errors['price_edit'] ?? ""; ?>
                    </div>

                    <input type="hidden" name="id_edit" value="<?php echo $product['product.id']; ?>">
                    <button name="update-product" type="submit" class="btn btn-warning w-100 text-white">Update <i class="fas fa-check"></i></button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <!-- Delete Product Modal -->
          <div class="modal fade" id="deleteProductModal<?php echo $product['product.id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-danger fw-bold" id="exampleModalLabel">DANGER!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  Are you sure that you want to delete product <strong>"<?php echo $product['product.name']; ?>"</strong>?
                </div>
                <div class="modal-footer">
                  <form method="POST" class="d-inline">
                    <input type="hidden" name="product_id" value="<?php echo $product['product.id'] ?? "-1"; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $product['product.name'] ?? "Error"; ?>">
                    <button name="delete-product" type="submit" class="btn btn-danger text-white">Yes</button>
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


<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <form method="POST">

          <div class="mb-4">
            <label class="form-label">Name <strong class="text-danger">*</strong></label>
            <input name="name" type="text" class="form-control <?php if (isset($errors['name'])) echo 'is-invalid';
                                                                else if (isset($name)) echo 'is-valid'; ?>" placeholder="Enter name" value="<?php echo $name ?? ""; ?>">
            <?php echo $errors['name'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Description <strong class="text-danger">*</strong></label>
            <textarea name="description" class="form-control <?php if (isset($errors['description'])) echo 'is-invalid';
                                                              else if (isset($description)) echo 'is-valid'; ?>" placeholder="Enter description" id="floatingTextarea2" style="height: 100px"><?php echo $description ?? ""; ?></textarea>
            <?php echo $errors['description'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Image <strong class="text-danger">*</strong></label>
            <input name="image" type="text" class="form-control <?php if (isset($errors['image']) || isset($errors['taken_image'])) echo 'is-invalid';
                                                                else if (isset($image)) echo 'is-valid'; ?>" placeholder="Enter image URL" value="<?php echo $image ?? ""; ?>">
            <?php echo $errors['image'] ?? ""; ?>
          </div>

          <div class="mb-4">
            <label class="form-label">Price <strong class="text-danger">*</strong></label>
            <input name="price" type="number" class="form-control <?php if (isset($errors['price']) || isset($errors['price'])) echo 'is-invalid'; ?>" placeholder="Enter price">
            <?php echo $errors['price'] ?? ""; ?>
          </div>

          <button name="add-product" type="submit" class="btn btn-primary w-100">Add <i class="fas fa-check"></i></button>

        </form>
      </div>
    </div>
  </div>
</div>

<?php include_once 'includes/footer.php' ?>