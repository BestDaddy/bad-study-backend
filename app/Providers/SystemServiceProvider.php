<?php

namespace App\Providers;


use App\Services\Courses\CoursesService;
use App\Services\Courses\CoursesServiceImpl;
use App\Services\Users\UsersService;
use App\Services\Users\UsersServiceImpl;
use Illuminate\Support\ServiceProvider;

class SystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind(UsersService::class, UsersServiceImpl::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UsersService::class, UsersServiceImpl::class);
        $this->app->bind(CoursesService::class, CoursesServiceImpl::class);
    }
}
