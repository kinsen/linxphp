<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cmn-Hans" xml:lang="cmn-Hans">
<head><meta charset="UTF-8">
<title>演示 PHP 自动清理缓存</title>
<meta content="演示 PHP 自动清理缓存" name="description">
<meta content="缓存,PHP,自动清理" name="keywords">
<meta content="演示 PHP 自动清理缓存" name="apple-mobile-web-app-title">
<meta content="webkit" name="renderer">
<meta content="telephone=no" name="format-detection">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0,minimal-ui" name="viewport">
</head><body>
<?php
/**
  * @author maas(maasdruck@gmail.com)
  * @date 2016/03/23
  * @version v1.03
  * @brief 演示 PHP 自动清理缓存
  */

// 缓存文件名
$word = array(1,2,3,4,5,6,7,8);
shuffle($word);
// 缓存时间 以秒数计算 如 1分钟=60 1天=86400 1周=604800 30天=2592000
$cachetime = 8;
// 间隔时间 删除缓存本身需要时间，所以加 1 秒
$interval = 1;
// 缓存路径 必须改成与已有目录不同的名字，以免造成无法挽回的损失
$path = 'example/';
if (!file_exists($path)) {
    mkdir($path, 0755);
}
if (!file_exists($path.'cache')) {
    file_put_contents($path.'cache', '');
}
// 缓存内容
ob_start();
echo rand(1,8);
$temp = ob_get_contents();
ob_end_clean();
file_put_contents($path.md5(urlencode($word[0])), $temp);
// 自动清理超时缓存
if ((time() - filemtime($path.'cache')) > $cachetime - $interval) {
    $od = opendir($path);
    while (($cache = readdir($od)) != false) {
        if($cache == '.' || $cache == '..') {
            continue;
        }
        if ((time() - filemtime($path.$cache)) > $cachetime) {
            unlink($path.$cache);
        }
    }
    closedir($od);
}

echo '<p>生成 '.md5(urlencode($word[0]));
echo '<br>如果 '.$cachetime.' 秒后<a href="javascript:location.reload()" target="_self">刷新</a>，自动删除刚贮存在 '.$path.' 目录下的这个缓存</p>';
?>
</body></html>