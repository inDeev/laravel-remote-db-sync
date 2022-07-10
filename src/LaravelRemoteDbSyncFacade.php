<?php

namespace Indeev\LaravelRemoteDbSync;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Indeev\LaravelRemoteDbSync\Skeleton\SkeletonClass
 */
class LaravelRemoteDbSyncFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-remote-db-sync';
    }
}
