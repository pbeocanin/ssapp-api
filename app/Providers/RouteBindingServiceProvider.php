<?php
/**
 * Created by PhpStorm.
 * User: fisher
 * Date: 2019-02-24
 * Time: 16:37
 */
namespace App\Providers;

use mmghv\LumenRouteBinding\RouteBindingServiceProvider as BaseServiceProvider;

class RouteBindingServiceProvider extends BaseServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot()
    {
        // The binder instance
        $binder = $this->binder;
        $binder->bind('user', 'App\User');

        // Here we define our bindings
    }
}