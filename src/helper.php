<?php

use Illuminate\Contracts\Foundation\Application;

if (! function_exists('ovalfi')) {

    /**
     * @return Application|mixed
     */
    function ovalfi()
    {
        return app('laravel-ovalfi');
    }
}
