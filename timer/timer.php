<?php

Swoole\Timer::tick(1000, function(){
    echo "timeout\n";
});

Swoole\Timer::tick(3000, function (int $timer_id, $param1, $param2) {
    echo "timer_id #$timer_id, after 3000ms.\n";
    echo "param1 is $param1, param2 is $param2.\n";

    Swoole\Timer::tick(1400, function ($timer_id) {
        echo "timer_id #$timer_id, after 14000ms.\n";
    });
}, "A", "B");