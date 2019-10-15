<?php

namespace Tianc\Elk\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Elasticsearch\Client;
use Tianc\Elk\Facades\ElasticSearchClient;
use Illuminate\Support\Facades\Cache;
class ElasticSearchLog implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $params;

    public $tries = 2;
    public $timeout = 10;
    /**
     * php artisan queue:work --queue=elk-log --sleep=3
     * Create a new job instance.
     * ElasticSearchLog constructor.
     * @param array $records
     */
    public function __construct(array $records)
    {
        $this->params['body'] = [];
        foreach ($records as $record) {
            unset($record['context']);
            unset($record['extra']);
            $record['datetime'] = $record['datetime']->format('Y-m-d H:i:s');
            $this->params['body'][] = [
                'index' => [
                    '_index' => config('elasticsearch.log_index'),
                    '_type' => config('elasticsearch.log_type'),
                ],
            ];
            $this->params['body'][] = $record;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(!Cache::add('lock_elklog_'.md5(json_encode($this->params)), true, 5)){
            // Log::error('lock_elklog_'.md5(json_encode($this->params)));
            return;
        }
        $client = ElasticSearchClient::getClient();
        if ($client instanceof Client) {
            $client->bulk($this->params);
        }
    }
}