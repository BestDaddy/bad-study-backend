<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return User
     */
    public function model(array $row)
    {
        return  User::updateOrCreate([
            'email'     => $row[0],
        ],[
            'first_name'    => $row[1] ?? 'student',
            'last_name'    => $row[2] ?? null,
            'password' => bcrypt('password'),
            'role_id' => Role::STUDENT_ID,
        ]);
    }
}
