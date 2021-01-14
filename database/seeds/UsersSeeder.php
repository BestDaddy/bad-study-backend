<?php


use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'id'   => Role::ADMIN_ID,
                'name' => 'Администратор',
            ],
            [
                'id'   => Role::STUDENT_ID,
                'name' => 'Пользователь'
            ],
            [
                'id'   => Role::TEACHER_ID,
                'name' => 'Преподаватель'
            ],
        ]);

        User::updateOrCreate(['email' => 'admin@mail.ru'],[
            'first_name' => "Admin",
            'password' => bcrypt('password'),
            'role_id' => Role::ADMIN_ID,
        ]);

        User::updateOrCreate(['email' => 'user@mail.ru'],[
            'first_name' => "Test user",
            'password' => bcrypt('password'),
            'role_id' => Role::STUDENT_ID,
        ]);

        User::updateOrCreate(['email' => 'teacher@mail.ru'],[
            'first_name' => "Test teacher",
            'password' => bcrypt('password'),
            'role_id' => Role::TEACHER_ID,
        ]);

    }
}
