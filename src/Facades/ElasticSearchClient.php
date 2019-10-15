<?php

namespace Tianc\Elk\Facades;

use Illuminate\Support\Facades\Facade;

class ElasticSearchClient extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'Tianc\Elk\Contracts\ElasticSearchClient';
    }
}