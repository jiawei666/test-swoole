<?php

go(function () {
    $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
    if (!$client->connect('127.0.0.1', 9501, 0.5)) {
        echo "connect failed. Error: {$client->errCode}\n";
    }
    $client->send("hello world\n");
    while (true) {
        $data = $client->recv();
//        var_dump("接收到数据：{$data}");
        if (strlen($data) > 0) {
            echo $data;
//            $client->send(time() . PHP_EOL);
        } else {
            if ($data === '') {
                // 全等于空 直接关闭连接
                $client->close();
                break;
            } else {
                if ($data === false) {
                    // 可以自行根据业务逻辑和错误码进行处理，例如：
                    // 如果超时时则不关闭连接，其他情况直接关闭连接
                    if ($client->errCode !== SOCKET_ETIMEDOUT) {
                        $client->close();
                        break;
                    }
                } else {
                    $client->close();
                    break;
                }
            }
        }
        \Co::sleep(1);
    }
});