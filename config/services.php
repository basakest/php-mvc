<?php

use App\Database;
use Framework\Container;

$container = new Container();
// 如果 set 的第二个参数是一个 object, 那么它的 __construct 会被调用
// 但并不是所有的请求都需要执行其中的逻辑
// $container->set(Database::class, new Database('127.0.0.1', 'mvc', 'root', ''));
$container->set(Database::class, function () {
    return new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
});

return $container;
