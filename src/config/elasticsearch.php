<?php

return [
    'hosts' => [
        env('ELASTIC_HOST',"127.0.0.1:9200")
    ],

    'log_index' => env('ELASTIC_LOG_INDEX','bf_log'),
    'log_type' => env('ELASTIC_LOG_TYPE','log'),
    'use_job' => env('ELASTIC_USE_QUEUE',false),
];