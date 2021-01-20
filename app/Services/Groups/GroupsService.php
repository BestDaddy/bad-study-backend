<?php


namespace App\Services\Groups;


use Illuminate\Http\Request;

interface GroupsService
{
    public function index();

    public function store(Request $request);
}
