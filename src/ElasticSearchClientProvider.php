<?php

namespace Tianc\Elk;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Tianc\Elk\Contracts\Foundation\ElasticSearchLogHandler;
use Illuminate\Foundation\Http\Kernel;
class ElasticSearchClientProvider extends ServiceProvider
{
    protected $middleware = [

        Tianc\Elk\Middleware\ElasticSearchLog::class
    ];
    /**
     * Bootstrap any application services.
     *
     * Written by Zhou Yubin(zhouyb@fengrongwang.com)
     */
    public function boot()
    {
        $this->registerPublishing();
        /**
         * 修改 Laravel 默认的 Log 存储方式为 Elasticsearch。
         */
        {
            $monolog = Log::getLogger();
            $elasticSearchLogHandler = new ElasticSearchLogHandler();
            // $monolog->popHandler(); // 把默认的文件存储去掉，否则会将日志同时存储到文件和ElasticSearch
            $monolog->pushHandler($elasticSearchLogHandler); // 添加 ElasticSearch 日志存储句柄
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // $this->registerMiddleware();
        $this->app->singleton('Tianc\Elk\Contracts\ElasticSearchClient', function ($app) {
            return new \Tianc\Elk\Contracts\Foundation\ElasticSearchClient();
        });
    }
    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/config/elasticsearch.php' => config_path('elasticsearch.php')]);
        }
    }
    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        // register route middleware.
        foreach ($this->middleware as $key => $middleware) {
           Kernel::pushMiddleware($middleware);
        }
    }
}