<?php

require_once "config/database.php";
require_once "includes/header.php";

if ($_SESSION['role'] !== 'admin') {

    echo "<div class='alert error'>Access denied.</div>";

    require_once "includes/footer.php";

    exit;
}

$result = $conn->query(
    "SELECT id, name, username, role, created_at
     FROM users
     ORDER BY id DESC"
);

?>

<h1>Users</h1>

<div class="table-container">

<table>

<thead>

<tr>

    <th>ID</th>
    <th>Name</th>
    <th>Username</th>
    <th>Role</th>
    <th>Created</th>

</tr>

</thead>

<tbody>

<?php while ($user = $result->fetch_assoc()): ?>

<tr>

    <td>
        <?php echo $user['id']; ?>
    </td>

    <td>
        <?php echo htmlspecialchars($user['name']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($user['username']); ?>
    </td>

    <td>
        <?php echo htmlspecialchars($user['role']); ?>
    </td>

    <td>
        <?php echo $user['created_at']; ?>
    </td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

<?php require_once "includes/footer.php"; ?>