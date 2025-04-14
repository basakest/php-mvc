{% extends "base.mvc.php" %}
{% block title %}Product Details{% endblock %}
{% block body %}
<h1>{{ product['name'] }}</h1>
<p>{{ product['description'] }}</p>
<a href="/products/{{ product['id'] }}/edit">Edit</a>
&nbsp;&nbsp;&nbsp;
<a href="/products/{{ product['id'] }}/delete">Delete</a>
{% endblock %}