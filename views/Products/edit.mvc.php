{% extends "base.mvc.php" %}
{% block title %}Edit Product{% endblock %}
{% block body %}
<h1>Edit Product</h1>
<form method="post" action="/products/{{ product['id'] }}/update">
    {% include "Products/form.php" %}
</form>
<p>
    <a href="/products/{{ product['id'] }}">Cancel</a>
</p>
</body>
</html>
{% endblock %}