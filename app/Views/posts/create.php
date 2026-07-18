@extends('layouts/main')

@section('title')
New post
@endsection

<h1>New post</h1>

<form method="post" action="/posts" enctype="multipart/form-data">
    @csrf

    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ e(old('title', '')) }}">
        @foreach(errors()['title'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <div>
        <label for="body">Body</label>
        <textarea id="body" name="body">{{ e(old('body', '')) }}</textarea>
        @foreach(errors()['body'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <div>
        <label for="image">Image (optional)</label>
        <input type="file" id="image" name="image">
        @foreach(errors()['image'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <button type="submit">Create</button>
</form>

<p><a href="/posts">Back to posts</a></p>
