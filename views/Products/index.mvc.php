<!DOCTYPE html>
<html>
<head>
    <title>{{ title }}</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <!-- herf 的地址不添加 / 时, 使用的是请求到的 html 的相对路径 -->
    <!--<link rel="stylesheet" type="text/css" href="/public/example.css">-->
</head>
<body>
<a href="/products/new">Create Product</a>
<p>total num: {{ totalNum }}</p>
<?php /* @var $products array */foreach ($products as $product): ?>
    <h2>
        <a href="/products/{{ product['id'] }}">
            {{ product['name'] }}
        </a>
    </h2>
<?php endforeach; ?>
</body>
</html>