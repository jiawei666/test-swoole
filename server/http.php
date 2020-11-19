<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);

$pdo = new PDO('mysql:host=localhost;dbname=54qj', 'root', 'Root1234.');
$pdo->query('set names utf8');


$http->on('request', function (swoole_http_request $request, swoole_http_response $response) use ($pdo) {
//    var_dump($request->server['request_uri']);
    // chrome 请求两次问题
    if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
        $response->end();
        return;
    }

    $ids = [1, 72, 73, 74];
    $id = $ids[array_rand($ids, 1)];
//    var_dump($id);
    $stmt = $pdo->query('SELECT * FROM  `users` WHERE `id`=:id');
    $stmt->bindValue(':id', $id);
    $user = $stmt->fetch();

//    var_dump($user);
    $response->header("Content-Type", "text/html; charset=utf-8");
    $response->write($user['realname']);
    $response->end();
    unset($stmt);

//    var_dump($request->get);

//    $response->write('我是袁嘉炜，号称第一猛男');
//    $response->end();
});

$http->start();