<?php
//php cli中，有三个系统常量，分别是stdin、stdout、stderr，代表文件句柄。

///**
// *@ 标准输入
// *@ php://stdin & stdin
// *@ stdin是一个文件句柄，等同于fopen("php://stdin", 'r')
//
// */
//$fh = fopen('php://stdin', 'r');
//echo "[php://stdin]请输入任意字符：";
//$str = fread($fh, 1000);
//echo "[php://stdin]你输入的是：".$str;
//fclose($fh);
//echo "[stdin]请输入任意字符：";
//$str = fread(STDIN, 1000);
//echo "[stdin]你输入的是：".$str;

/**
 *@ 标准输出
 *@ php://stdout & stdout
 *@ stdout是一个文件句柄，等同于fopen("php://stdout", 'w')
 */
$fh = fopen('php://stdout', 'w');
fwrite($fh, "标准输出php://stdout/\n");
//fclose($fh);
//fwrite(STDOUT, "标准输出stdout/n");

///**
// *@ 标准错误，默认情况下会发送至用户终端
// *@ php://stderr & stderr
// *@ stderr是一个文件句柄，等同于fopen("php://stderr", 'w')
// */
//$fh = fopen('php://stderr', 'w');
//fwrite($fh, "标准错误php://stderr/n");
//fclose($fh);
//fwrite(stderr, "标准错误stderr/n");