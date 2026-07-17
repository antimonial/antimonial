@extends('layouts/main')

@section('title')
Active users
@endsection

<h1>Active users</h1>

<ul>
@foreach($users as $user)
    <li>{{ $user->name }}</li>
@endforeach
</ul>
