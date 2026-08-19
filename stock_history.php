<?php

require_once "config/database.php";
require_once "includes/header.php";

$result = $conn->query(
    "SELECT
        st.id,
        p.product_name,
        u.name AS user_name,
        st.transaction_type,
        st.quantity,
        st.transaction_date
     FROM stock_transactions st
     INNER JOIN products p
        ON st.product_id = p.id
     LEFT JOIN users u
        ON st.user_id = u.id
     ORDER BY st.transaction_date DESC"
);

?>

<h1>Stock Transaction History</h1>

<div class="table-container">

<table>

<thead>

<tr>

    <th>ID</th>
    <th>Product</th>
    <th>Type</th>
    <th>Quantity</th>
    <th>User</th>
    <th>Date</th>

</tr>

</thead>

<tbody>

<?php if ($result->num_rows > 0): ?>

    <?php while ($row = $result->fetch_assoc()): ?>

        <tr>

            <td>
                <?php echo $row['id']; ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['product_name']); ?>
            </td>

            <td>

                <?php if ($row['transaction_type'] === 'IN'): ?>

                    <span class="badge success">
                        Stock In
                    </span>

                <?php else: ?>

                    <span class="badge danger">
                        Stock Out
                    </span>

                <?php endif; ?>

            </td>

            <td>
                <?php echo $row['quantity']; ?>
            </td>

            <td>
                <?php
                echo htmlspecialchars(
                    $row['user_name'] ?? 'Unknown'
                );
                ?>
            </td>

            <td>
                <?php echo $row['transaction_date']; ?>
            </td>

        </tr>

    <?php endwhile; ?>

<?php else: ?>

    <tr>

        <td colspan="6">
            No transactions found.
        </td>

    </tr>

<?php endif; ?>

</tbody>

</table>

</div>

<?php require_once "includes/footer.php"; ?>