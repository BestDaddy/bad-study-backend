<?php


namespace App\Services\Users;


use Illuminate\Http\Request;

interface UsersService
{
    public function index();

    public function store(Request $request);

    public function getUser($id);
}
