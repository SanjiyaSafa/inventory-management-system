<?php

require_once "config/database.php";

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

        $stmt = $conn->prepare(
            "INSERT INTO products
            (product_name, category, price, quantity, supplier, minimum_stock)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssdisi",
            $product_name,
            $category,
            $price,
            $quantity,
            $supplier,
            $minimum_stock
        );

        if ($stmt->execute()) {

            header("Location: products.php");
            exit;

        } else {

            $error = "Failed to add product.";

        }

        $stmt->close();
    }
}

require_once "includes/header.php";

?>

<h1>Add Product</h1>

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
        required
    >

    <label>Category</label>

    <input
        type="text"
        name="category"
        required
    >

    <label>Price</label>

    <input
        type="number"
        name="price"
        step="0.01"
        min="0"
        required
    >

    <label>Quantity</label>

    <input
        type="number"
        name="quantity"
        min="0"
        required
    >

    <label>Supplier</label>

    <input
        type="text"
        name="supplier"
    >

    <label>Minimum Stock</label>

    <input
        type="number"
        name="minimum_stock"
        min="0"
        value="5"
        required
    >

    <button type="submit" class="btn">
        Add Product
    </button>

    <a href="products.php" class="btn secondary">
        Cancel
    </a>

</form>

<?php require_once "includes/footer.php"; ?>