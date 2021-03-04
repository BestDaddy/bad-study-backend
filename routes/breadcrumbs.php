<?php
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

//Courses
Breadcrumbs::for('courses', function ($trail) {
    $trail->push('Курсы', route('courses.index'));
});

Breadcrumbs::for('courses.show', function ($trail, $course) {
    $trail->parent('courses');
    $trail->push($course->name, route('courses.show', $course->id));
});


Breadcrumbs::for('chapters.show', function ($trail, $chapter) {
    $trail->parent('courses.show', $chapter->course);
    $trail->push($chapter->name, route('chapters.show', $chapter->id));
});


//Groups

Breadcrumbs::for('groups', function ($trail) {
    $trail->push('Группы', route('groups.index'));
});

Breadcrumbs::for('groups.show', function ($trail, $group) {
    $trail->parent('groups');
    $trail->push($group->name, route('groups.show', $group->id));
});

Breadcrumbs::for('schedules.index', function ($trail, $group, $course) {
    $trail->parent('groups.show', $group);
    $trail->push($course->name, route('groups.courses.schedules.index', [$group, $course]));
});

Breadcrumbs::for('schedules.show', function ($trail, $group, $course, $schedule) {
    $trail->parent('schedules.index', $group, $course);
    $trail->push($schedule->starts_at, route('groups.courses.schedules.show', [$group, $course, $schedule]));
});

Breadcrumbs::for('schedules.user.results', function ($trail,  $schedule, $user) {
    $trail->parent('schedules.show', $schedule->group, $schedule->chapter->course, $schedule);
    $trail->push($user->first_name, route('userResults', [$schedule->id, $user->id]));
});
