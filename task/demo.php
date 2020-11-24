<?php
$serv = new Swoole\Server("127.0.0.1", 9502);

//设置异步任务的工作进程数量
$serv->set(array('task_worker_num' => 4));

//此回调函数在worker进程中执行
$serv->on('receive', function(Swoole\Server $serv, $fd, $from_id, $data) {
    //投递异步任务
    $task_id = $serv->task($data);
    echo "worker进程 AsyncTask: id=$task_id,fd=$fd,form_id=$from_id,data=$data\n";
});

//处理异步任务(此回调函数在task进程中执行)
$serv->on('task', function (Swoole\Server $serv, $task_id, $from_id, $data) {
    echo "task进程 AsyncTask[id=$task_id,from_id=$from_id,data=$data]".PHP_EOL;
    //返回任务执行的结果
    $serv->finish($data);
    echo 'task里面' . PHP_EOL;
});

echo 'task跟finish之间' . PHP_EOL;

//处理异步任务的结果(此回调函数在worker进程中执行)
$serv->on('finish', function (Swoole\Server $serv, $task_id, $data) {
    echo "AsyncTask[$task_id] 处理完成: $data".PHP_EOL;
});

$serv->start();
