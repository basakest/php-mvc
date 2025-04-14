{% extends "base.mvc.php" %}
{% block title %}Products{% endblock %}
{% block body %}
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
{% endblock %}