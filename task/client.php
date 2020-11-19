<?php

class Client
{
    private $client;

    public function __construct()
    {
        $this->client = new Swoole\Coroutine\Client(SWOOLE_SOCK_TCP);
        $this->client->recv();
        $this->client->on('Close', [$this, 'onClose']);
        $this->client->on('Error', [$this, 'onError']);
    }

    public function connect()
    {
        if (!$fp = $this->client->connect('127.0.0.1', 9501, 1)) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]" . PHP_EOL;

            return;
        } else {
            fwrite(STDOUT, '输入Email:');
            swoole_event_add(
                STDIN,
                function () {
                    fwrite(STDOUT, '输入Email:');
                    $msg = trim(fgets(STDIN));
                    $this->send($msg);
                }
            );
        }
    }


    public function onReceive($cli, $data)
    {
        echo PHP_EOL . 'Received: ' . $data . PHP_EOL;
    }

    public function send($data)
    {
        $this->client->send($data);
    }

    public function onClos($cli)
    {
        echo 'Client close connection' . PHP_EOL;
    }

    public function onError()
    {
    }
}

$client = new Client();
$client->connect();
