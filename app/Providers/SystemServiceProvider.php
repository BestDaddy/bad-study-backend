<?php

namespace App\Providers;


use App\Services\Courses\ChaptersService;
use App\Services\Courses\ChaptersServiceImpl;
use App\Services\Courses\CoursesService;
use App\Services\Courses\CoursesServiceImpl;
use App\Services\Courses\ExercisesService;
use App\Services\Courses\ExercisesServiceImpl;
use App\Services\Groups\GroupsService;
use App\Services\Groups\GroupsServiceImpl;
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
        $this->app->bind(ChaptersService::class, ChaptersServiceImpl::class);
        $this->app->bind(ExercisesService::class, ExercisesServiceImpl::class);
        $this->app->bind(GroupsService::class, GroupsServiceImpl::class);
    }
}
