@extends('layouts/main')

@section('title')
Log in
@endsection

<h1>Log in</h1>

<form method="post" action="/login">
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

    <button type="submit">Log in</button>
</form>

<p><a href="/register">No account? Register</a></p>
