<?php

class Client
{
    private $client;

    public function __construct()
    {
        Co\run(function(){
            $client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
            if (!$fp = $client->connect('127.0.0.1', 9501, 1)) {
                echo "Error: {$fp->errMsg}[{$fp->errCode}]" . PHP_EOL;

                return;
            } else {
                fwrite(STDOUT, '输入Email:');
                // 异步，swoole_event_add不会阻塞代码(相当于这是个yield语句的迭代器。每当接收到stdin的数据流，就会执行yield，启用系统调用（匿名函数）)
                swoole_event_add(
                    STDIN,
                    function () use ($client) {
                        $msg = trim(fgets(STDIN));
                        echo "数据已发送，你可以继续输入数据："  . PHP_EOL;
                        $client->send($msg);
//                        fwrite(STDOUT, '输入Email:');
                    }
                );

                while (true) {
                    $data = $client->recv();
                    if (strlen($data) > 0) {
                        fwrite(STDOUT, "接受到数据 - {$data}" . PHP_EOL);
                    }
                    \Co::sleep(1);
                }
            }
        });


    }



    public function onReceive($cli, $data)
    {
        echo PHP_EOL . 'Received: ' . $data . PHP_EOL;
    }

}

$client = new Client();
