<?php

require_once "config/database.php";
require_once "includes/header.php";

$search = $_GET['search'] ?? '';

$stmt = $conn->prepare(
    "SELECT * FROM products
     WHERE product_name LIKE ?
     OR category LIKE ?
     OR supplier LIKE ?
     ORDER BY id DESC"
);

$searchTerm = "%" . $search . "%";

$stmt->bind_param(
    "sss",
    $searchTerm,
    $searchTerm,
    $searchTerm
);

$stmt->execute();

$result = $stmt->get_result();

?>

<div class="page-header">

    <h1>Products</h1>

    <a href="add_product.php" class="btn">
        + Add Product
    </a>

</div>

<div class="table-container">

<table>

<thead>

<tr>

    <th>ID</th>
    <th>Product</th>
    <th>Category</th>
    <th>Price</th>
    <th>Quantity</th>
    <th>Supplier</th>
    <th>Minimum Stock</th>
    <th>Status</th>
    <th>Actions</th>

</tr>

</thead>

<tbody>

<?php if ($result->num_rows > 0): ?>

    <?php while ($product = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?php echo $product['id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($product['product_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($product['category']); ?>
            </td>

            <td>
                ৳<?php echo number_format($product['price'], 2); ?>
            </td>

            <td>
                <?php echo $product['quantity']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($product['supplier']); ?>
            </td>

            <td>
                <?php echo $product['minimum_stock']; ?>
            </td>

            <td>

                <?php if ($product['quantity'] <= $product['minimum_stock']): ?>

                    <span class="badge danger">
                        Low Stock
                    </span>

                <?php else: ?>

                    <span class="badge success">
                        Available
                    </span>

                <?php endif; ?>

            </td>

            <td>

                <a
                    href="edit_product.php?id=<?php echo $product['id']; ?>"
                    class="btn small"
                >
                    Edit
                </a>

                <?php if ($_SESSION['role'] === 'admin'): ?>

                    <a
                        href="delete_product.php?id=<?php echo $product['id']; ?>"
                        class="btn small danger-btn"
                        onclick="return confirm('Are you sure you want to delete this product?');"
                    >
                        Delete
                    </a>

                <?php endif; ?>

            </td>

        </tr>

    <?php endwhile; ?>

<?php else: ?>

    <tr>

        <td colspan="9">
            No products found.
        </td>

    </tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once "includes/footer.php"; ?>
