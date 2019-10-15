<?php

namespace Tianc\Elk\Middleware;

use Closure;
use Tianc\Elk\Jobs\ElasticSearchLog as JElasticSearchLog;
use Tianc\Elk\Facades\ElasticSearchClient;

class ElasticSearchLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param $request
     * @param $response
     * Written by Zhou Yubin(zhouyb@fengrongwang.com)
     */
    public function terminate($request, $response)
    {
        $documents = ElasticSearchClient::getDocuments();
        // 需要判断是否有日志
        if (count($documents) > 0) {
            if(config('elasticsearch.use_queue')===true){
                JElasticSearchLog::dispatch($documents)->onQueue('elk-log');
            }else{
                JElasticSearchLog::dispatchNow($documents);
            }
        }
    }
}