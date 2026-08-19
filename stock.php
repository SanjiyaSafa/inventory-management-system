<?php

require_once "config/database.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $product_id = intval($_POST['product_id']);
    $type = $_POST['transaction_type'];
    $quantity = intval($_POST['quantity']);

    if ($product_id <= 0 || $quantity <= 0) {

        $error = "Please enter valid stock information.";

    } elseif (!in_array($type, ['IN', 'OUT'])) {

        $error = "Invalid transaction type.";

    } else {

        $stmt = $conn->prepare(
            "SELECT quantity
             FROM products
             WHERE id = ?"
        );

        $stmt->bind_param("i", $product_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {

            $error = "Product not found.";

        } else {

            $product = $result->fetch_assoc();

            $current_quantity = intval($product['quantity']);

            if ($type === 'OUT' && $quantity > $current_quantity) {

                $error = "Not enough stock available.";

            } else {

                $conn->begin_transaction();

                try {

                    if ($type === 'IN') {

                        $new_quantity =
                            $current_quantity + $quantity;

                    } else {

                        $new_quantity =
                            $current_quantity - $quantity;
                    }

                    $update = $conn->prepare(
                        "UPDATE products
                         SET quantity = ?
                         WHERE id = ?"
                    );

                    $update->bind_param(
                        "ii",
                        $new_quantity,
                        $product_id
                    );

                    $update->execute();

                    $transaction = $conn->prepare(
                        "INSERT INTO stock_transactions
                        (product_id, user_id, transaction_type, quantity)
                        VALUES (?, ?, ?, ?)"
                    );

                    $transaction->bind_param(
                        "iisi",
                        $product_id,
                        $_SESSION['user_id'],
                        $type,
                        $quantity
                    );

                    $transaction->execute();

                    $conn->commit();

                    $message = "Stock transaction completed successfully.";

                } catch (Exception $e) {

                    $conn->rollback();

                    $error = "Stock transaction failed.";

                }
            }
        }
    }
}

$products = $conn->query(
    "SELECT id, product_name, quantity
     FROM products
     ORDER BY product_name"
);

require_once "includes/header.php";

?>

<h1>Stock Management</h1>

<?php if ($message): ?>

    <div class="alert success">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php endif; ?>

<?php if ($error): ?>

    <div class="alert error">
        <?php echo htmlspecialchars($error); ?>
    </div>

<?php endif; ?>

<form method="POST" class="form-card">

    <label>Product</label>

    <select name="product_id" required>

        <option value="">
            Select Product
        </option>

        <?php while ($product = $products->fetch_assoc()): ?>

            <option value="<?php echo $product['id']; ?>">

                <?php
                echo htmlspecialchars(
                    $product['product_name']
                );
                ?>

                (Current:
                <?php echo $product['quantity']; ?>)

            </option>

        <?php endwhile; ?>

    </select>

    <label>Transaction Type</label>

    <select name="transaction_type" required>

        <option value="IN">
            Stock In
        </option>

        <option value="OUT">
            Stock Out
        </option>

    </select>

    <label>Quantity</label>

    <input
        type="number"
        name="quantity"
        min="1"
        required
    >

    <button type="submit" class="btn">
        Update Stock
    </button>

</form>

<?php require_once "includes/footer.php"; ?>