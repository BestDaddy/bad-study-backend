<?php

namespace App\Providers;


use App\Services\BaseService;
use App\Services\BaseServiceImpl;
use App\Services\Courses\ChaptersService;
use App\Services\Courses\ChaptersServiceImpl;
use App\Services\Courses\CoursesService;
use App\Services\Courses\CoursesServiceImpl;
use App\Services\Courses\ExerciseResultsService;
use App\Services\Courses\ExerciseResultsServiceImpl;
use App\Services\Courses\ExercisesService;
use App\Services\Courses\ExercisesServiceImpl;
use App\Services\Groups\AttendancesService;
use App\Services\Groups\AttendancesServiceImpl;
use App\Services\Groups\GroupsService;
use App\Services\Groups\GroupsServiceImpl;
use App\Services\Groups\SchedulesService;
use App\Services\Groups\SchedulesServiceImpl;
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
        $this->app->bind(SchedulesService::class, SchedulesServiceImpl::class);
        $this->app->bind(BaseService::class, BaseServiceImpl::class);
        $this->app->bind(AttendancesService::class, AttendancesServiceImpl::class);
        $this->app->bind(ExerciseResultsService::class, ExerciseResultsServiceImpl::class);

    }
}
