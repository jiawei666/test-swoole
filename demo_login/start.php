<?php
$http = new Swoole\Http\Server('0.0.0.0', 9501);

//使用内存 SESSION~~
$http->_GLOBAL_SESSION = [];
$http->pdo             = new PDO('mysql:host=localhost;dbname=54qj', 'root', 'Root1234.');
$http->db              = new \stdClass();

// 使用预加载, 提前将用户数据加载到内存. 登录都无需网络/磁盘IO
if ('user') {
    echo "加载用户数据\n";
    $http->db->user = [];
    $stmt           = $http->pdo->query('select * from users where id <=75');
    $users          = $stmt->fetchAll();
    foreach ($users as $i => $user) {
        $http->db->user[$user['id']] = $user;
    }

    echo "用户数据加载完成\n\n";
    unset($user);
    unset($users);
}

$http->on('request', function (swoole_http_request $req, swoole_http_response $res) use ($http) {
    if (!isset($req->cookie) || !isset($req->cookie['sid']) || !$req->cookie['sid']) {
        $req->cookie['sid'] = md5(password_hash(time() . mt_rand(100000, 999999), 1));
        $res->cookie(
            'sid',
            $req->cookie['sid'],
            time() + 60 * 60 * 24,
            '/',
            '',
            false,
            true
        );
    }

    $_SESS_ID = $req->cookie['sid'];

    if (!isset($http->_GLOBAL_SESSION[$_SESS_ID])) {
        $http->_GLOBAL_SESSION[$_SESS_ID] = [];
    }
    $_SESSION = &$http->_GLOBAL_SESSION[$_SESS_ID];

    if ($req->server['request_uri'] == '/') {
        // 重定向到login页面
        $res->status(302);
        $res->header('Location', '/login/');
        $res->end();

        return;
    } elseif ($req->server['request_uri'] == '/login/') {
        if (isset($_SESSION['user'])) {
            // 重定向到用户详情页面
            $res->status(302);
            $res->header('Location', '/i/');
            $res->end();

            return;
        }

        $html = file_get_contents(dirname(__FILE__) . '/tpl/' . 'login.html');
        $res->write($html);
        $res->end();

        unset($html);

        return;
    } elseif ($req->server['request_uri'] == '/dologin/') {
        $user = $http->db->user[$req->post['id']];

        if (!$user || $req->post['account'] <> $user['account'] ) {
            $res->write('bad_account_or_password');
            $res->end();

            return;
        }
        $_SESSION['user'] = $user;
        unset($user);

        $res->status(302);
        $res->header('Location', '/i/');
        $res->end();

        return;
    } elseif ($req->server['request_uri'] == '/i/') {
        if (!isset($_SESSION['user'])) {
            // 重定向到用户详情页面
            $res->status(302);
            $res->header('Location', '/login/');
            $res->end();

            return;
        }

        $res->write('You currently logged in as ' . $_SESSION['user']['account']);
        $res->end();

        return;
    }

    $res->status(404);
    $res->end();
});

$http->start();

//$http->
