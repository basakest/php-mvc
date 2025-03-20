<?php

require './model.php';

$products = (new Model())->getData();

require './view.php';
