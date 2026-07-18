@extends('layouts/main')

@section('title')
Register
@endsection

<h1>Register</h1>

<form method="post" action="/register">
    @csrf

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ e(old('email', '')) }}">
        @foreach(errors()['email'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        @foreach(errors()['password'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <button type="submit">Register</button>
</form>

<p><a href="/login">Already have an account? Log in</a></p>
