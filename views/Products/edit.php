<h1>Edit Product</h1>
<form method="post" action="/products/<?= $product['id']; ?>/update">
    <?php require 'form.php'; ?>
</form>
<p>
    <a href="/products/<?= $product['id']; ?>">Cancel</a>
</p>
</body>
</html>