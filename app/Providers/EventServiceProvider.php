<?php

namespace App\Providers;

use App\Listeners\SyncScheduleCreated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\ScheduleCreated' => [
            'App\Listeners\SyncScheduleCreated'
        ],
        'App\Events\ExerciseResultScored' => [
            'App\Listeners\SyncExerciseResultScored'
        ],
    ];

//    protected $subscribe = [
//        SyncScheduleCreated::class,
//    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
