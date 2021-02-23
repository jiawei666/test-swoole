<?php
// 携程初探（非携程状态下进行时间对比）
$s = microtime(true);

//for ($c = 100; $c--;) {
//    for ($n = 100; $n--;) {
//        usleep(1000);
//    }
//}

// 10k file read and write
for ($c = 1000; $c--;) {
    for ($n = 10000; $n--;) {
        $tmp_filename = "/tmp/test2-{$c}-{$n}.php";
        $self = file_get_contents(__FILE__);
        file_put_contents($tmp_filename, $self);
        assert(file_get_contents($tmp_filename) === $self);
                unlink($tmp_filename);
    }
}

echo 'use ' . (microtime(true) - $s) . ' s' . PHP_EOL;