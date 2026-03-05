<?php
// 允许跨域
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");

// 远程链接文件地址
$remoteUrl = 'https://fh.govs.xin/links_map.txt';

// 读取远程文件
$content = file_get_contents($remoteUrl);
if ($content === false) {
    http_response_code(500);
    echo "读取失败";
} else {
    echo $content;
}
?>