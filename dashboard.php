<?php

require_once "config/database.php";
require_once "includes/header.php";

$total_products = 0;
$total_quantity = 0;
$low_stock = 0;
$total_value = 0;

$result = $conn->query(
    "SELECT
        COUNT(*) AS total_products,
        COALESCE(SUM(quantity), 0) AS total_quantity,
        COALESCE(SUM(quantity * price), 0) AS total_value
     FROM products"
);

if ($result) {

    $data = $result->fetch_assoc();

    $total_products = $data['total_products'];
    $total_quantity = $data['total_quantity'];
    $total_value = $data['total_value'];
}

$result = $conn->query(
    "SELECT COUNT(*) AS low_stock
     FROM products
     WHERE quantity <= minimum_stock"
);

if ($result) {

    $data = $result->fetch_assoc();

    $low_stock = $data['low_stock'];
}

?>

<h1>Dashboard</h1>

<p>
    Welcome,
    <strong>
        <?php echo htmlspecialchars($_SESSION['name']); ?>
    </strong>
</p>

<div class="dashboard-grid">

    <div class="card">

        <h3>Total Products</h3>

        <p class="number">
            <?php echo $total_products; ?>
        </p>

    </div>

    <div class="card">

        <h3>Total Stock</h3>

        <p class="number">
            <?php echo $total_quantity; ?>
        </p>

    </div>

    <div class="card">

        <h3>Low Stock Items</h3>

        <p class="number warning">
            <?php echo $low_stock; ?>
        </p>

    </div>

    <div class="card">

        <h3>Inventory Value</h3>

        <p class="number">
            ৳<?php echo number_format($total_value, 2); ?>
        </p>

    </div>

</div>

<div class="section">

    <h2>Quick Actions</h2>

    <a href="add_product.php" class="btn">
        Add Product
    </a>

    <a href="stock.php" class="btn">
        Manage Stock
    </a>

    <a href="products.php" class="btn">
        View Products
    </a>

</div>

<?php require_once "includes/footer.php"; ?>