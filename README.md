# elk-for-laravel

Requirements
------------
 - PHP >= 7.2.0
 - Laravel >= 5.8.0
 
 
Installation
------------
- 1.安装包
```
composer require tianc/elk-for-laravel
```
- 2.在 app/Http/Kernel.php 添加   
```
\Tianc\Elk\Middleware\ElasticSearchLog::class
```
如
```
protected $middleware = [  
    ...  
    \Tianc\Elk\Middleware\ElasticSearchLog::class,  
];  
```
- 3.执行 
```
php artisan vendor:publish --providor="Tianc\Elk\ElasticSearchClientProvider"
```

- 4.修改配置文件（config/elasticsearch.php)
```
<?php
return [
    'hosts' => [
        env('ELASTIC_HOST',"127.0.0.1:9200")//elk的host
    ],

    'log_index' => env('ELASTIC_LOG_INDEX','bf_log'),//索引
    'log_type' => env('ELASTIC_LOG_TYPE','log'),//文档
    'use_job' => env('ELASTIC_USE_QUEUE',false),//是否使用队列
];
```
感谢
------------
@zobeen(https://my.oschina.net/zobeen/blog/2250157)
