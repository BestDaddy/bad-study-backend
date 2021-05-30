@extends('layouts.admin')

@section('content')
    <h1>Welcome to Know IITU project!</h1>
    <h2>{{Auth::user()->first_name .' '. Auth::user()->last_name}}</h2>
@endsection
