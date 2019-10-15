<?php

namespace Tianc\Elk\Contracts\Foundation;

use Monolog\Handler\AbstractProcessingHandler;
use Tianc\Elk\Facades\ElasticSearchClient;

class ElasticSearchLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record)
    {
        // Log::debug('debug');//100
        // Log::info('info');//200
        // Log::notice('notice');//250
        // Log::warning('warning');//300
        // Log::error('error');//400
        // Log::critical('critical');//500
        // Log::alert('alert');//550
        // Log::emergency('emergency');//600
        if ($record['level'] >= 200) {
            ElasticSearchClient::addDocument($record);
        }
    }
}