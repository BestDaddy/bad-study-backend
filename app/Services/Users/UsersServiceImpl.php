<?php


namespace App\Services\Users;


use App\Models\User;

class UsersServiceImpl implements UsersService
{
    public function index(){
        return User::all();
    }
}
