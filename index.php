<?php

$dsn = 'mysql:dbname=mvc;host=127.0.0.1;charset=UTF8';
$user = 'root';
$password = '';

$pdo = new PDO($dsn, $user, $password);
$sql = 'SELECT * FROM `product`;';
$statement = $pdo->query($sql,  PDO::FETCH_ASSOC);
$products = $statement->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <meta charset="UTF-8">
</head>
<body>
<?php foreach ($products as $product): ?>
    <h2>
        <?= htmlspecialchars($product['name']); ?>
    </h2>
    <p>
        <?= htmlspecialchars($product['description']); ?>
    </p>
<?php endforeach; ?>
</body>
</html>
