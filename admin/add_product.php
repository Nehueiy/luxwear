<?php
require '../inc/db.php';
session_start();
if (!isset($_SESSION['user']) || !$_SESSION['user']['is_admin']) {
  header('Location: login.php'); exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = $_POST['title'];
  $slug = $_POST['slug'];
  $desc = $_POST['description'];
  $price = (float)$_POST['price'];
  $inventory = (int)$_POST['inventory'];
  $category_id = (int)$_POST['category_id'];

  // handle upload
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['image']['tmp_name'];
    $name = basename($_FILES['image']['name']);
    $target = __DIR__ . '/../uploads/' . time() . '_' . $name;
    if (!move_uploaded_file($tmp, $target)) {
      $errors[] = "Upload failed";
    } else {
      $image_path = 'uploads/' . basename($target);
    }
  } else {
    $image_path = 'assets/images/placeholder.png';
  }

  if (empty($errors)) {
    $stmt = $mysqli->prepare("INSERT INTO products (title, slug, description, price, inventory, image, category_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssdisi', $title, $slug, $desc, $price, $inventory, $image_path, $category_id);
    if ($stmt->execute()) {
      header('Location: dashboard.php'); exit;
    } else {
      $errors[] = "DB error: " . $mysqli->error;
    }
  }
}

require '../inc/header.php';
// fetch categories
$catR = $mysqli->query("SELECT * FROM categories");
$cats = $catR->fetch_all(MYSQLI_ASSOC);
?>
<h1>Add Product</h1>
<?php foreach($errors as $e) echo "<p style='color:red;'>$e</p>"; ?>
<form method="post" action="add_product.php" enctype="multipart/form-data">
  <div class="form-field"><input type="text" name="title" placeholder="Title" required></div>
  <div class="form-field"><input type="text" name="slug" placeholder="slug (unique)" required></div>
  <div class="form-field"><textarea name="description" placeholder="Description"></textarea></div>
  <div class="form-field"><input type="text" name="price" placeholder="Price" required></div>
  <div class="form-field"><input type="number" name="inventory" placeholder="Inventory" required></div>
  <div class="form-field">
    <select name="category_id">
      <?php foreach($cats as $c): ?>
        <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="form-field"><input type="file" name="image"></div>
  <button class="btn" type="submit">Add Product</button>
</form>
<?php require '../inc/footer.php'; ?>
