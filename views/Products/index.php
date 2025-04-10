<?php /* @var $products array */foreach ($products as $product): ?>
    <h2>
        <a href='/products/<?= $product['id'] ?>'>
            <?= htmlspecialchars($product['name']); ?>
        </a>
    </h2>
<?php endforeach; ?>
</body>
</html>