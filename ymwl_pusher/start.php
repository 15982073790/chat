<?php
use Workerman\Worker;
use Workerman\Lib\Timer;

// composer autoload
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Pusher.php';
require_once __DIR__ . '/config.php';

//WSS证书', '一般为fullchain.pem，宝塔默认位置：/www/server/panel/vhost/ssl/YOU DOMAIN/fullchain.pem'
$ws_ssl_cert='';
//WSS私钥', '一般为privkey.pem，宝塔默认位置：/www/server/panel/vhost/ssl/YOU DOMAIN/privkey.pem'
$ws_ssl_pk='';
//&& strpos($whost,'wss://')!==false
if(trim($ws_ssl_cert) && trim($ws_ssl_pk)){
    if (!is_file($ws_ssl_cert)) exit("file $ws_ssl_cert not exist\n");
    if (!is_file($ws_ssl_pk)) exit("file $ws_ssl_pk not exist\n");
    $context = array(
        'ssl' => array(
            'local_cert'  => $ws_ssl_cert,
            'local_pk'    => $ws_ssl_pk,
            'verify_peer' => false,
        )
    );
}else{
    $context=[];
}
$pusher = new Pusher\Pusher("websocket://0.0.0.0:$websocket_port",$context);
$pusher->apiListen = "http://0.0.0.0:$api_port";
if(trim($ws_ssl_cert) && trim($ws_ssl_pk)){
    $pusher->transport = 'ssl';
}

$pusher->appInfo = array(
    $app_key => array(
        'channel_hook' => "{$domain}/admin/event",
        'app_secret'   => $app_secret,
    ),
);


// 只能是1
$pusher->count = 1;

Worker::runAll();