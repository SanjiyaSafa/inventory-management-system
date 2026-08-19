<?php

require_once "config/database.php";

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: products.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT * FROM products WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    header("Location: products.php");
    exit;

}

$product = $result->fetch_assoc();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $product_name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $quantity = intval($_POST['quantity']);
    $supplier = trim($_POST['supplier']);
    $minimum_stock = intval($_POST['minimum_stock']);

    if ($product_name === "" || $category === "") {

        $error = "Product name and category are required.";

    } elseif ($price < 0 || $quantity < 0 || $minimum_stock < 0) {

        $error = "Values cannot be negative.";

    } else {

        $update = $conn->prepare(
            "UPDATE products
             SET product_name = ?,
                 category = ?,
                 price = ?,
                 quantity = ?,
                 supplier = ?,
                 minimum_stock = ?
             WHERE id = ?"
        );

        $update->bind_param(
            "ssdisii",
            $product_name,
            $category,
            $price,
            $quantity,
            $supplier,
            $minimum_stock,
            $id
        );

        if ($update->execute()) {

            header("Location: products.php");
            exit;

        } else {

            $error = "Failed to update product.";

        }
    }
}

require_once "includes/header.php";

?>

<h1>Edit Product</h1>

<?php if ($error): ?>

    <div class="alert error">
        <?php echo htmlspecialchars($error); ?>
    </div>

<?php endif; ?>

<form method="POST" class="form-card">

    <label>Product Name</label>

    <input
        type="text"
        name="product_name"
        value="<?php echo htmlspecialchars($product['product_name']); ?>"
        required
    >

    <label>Category</label>

    <input
        type="text"
        name="category"
        value="<?php echo htmlspecialchars($product['category']); ?>"
        required
    >

    <label>Price</label>

    <input
        type="number"
        name="price"
        step="0.01"
        min="0"
        value="<?php echo $product['price']; ?>"
        required
    >

    <label>Quantity</label>

    <input
        type="number"
        name="quantity"
        min="0"
        value="<?php echo $product['quantity']; ?>"
        required
    >

    <label>Supplier</label>

    <input
        type="text"
        name="supplier"
        value="<?php echo htmlspecialchars($product['supplier']); ?>"
    >

    <label>Minimum Stock</label>

    <input
        type="number"
        name="minimum_stock"
        min="0"
        value="<?php echo $product['minimum_stock']; ?>"
        required
    >

    <button type="submit" class="btn">
        Update Product
    </button>

    <a href="products.php" class="btn secondary">
        Cancel
    </a>

</form>

<?php require_once "includes/footer.php"; ?>