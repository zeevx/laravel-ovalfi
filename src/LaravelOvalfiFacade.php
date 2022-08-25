<?php

namespace Zeevx\LaravelOvalfi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zeevx\LaravelOvalfi\Skeleton\SkeletonClass
 */
class LaravelOvalfiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-ovalfi';
    }
}
