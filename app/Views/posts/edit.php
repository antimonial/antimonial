@extends('layouts/main')

@section('title')
Edit post
@endsection

<h1>Edit post</h1>

<form method="post" action="/posts/{{ $post->id }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="{{ e(old('title', $post->title)) }}">
        @foreach(errors()['title'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    <div>
        <label for="body">Body</label>
        <textarea id="body" name="body">{{ e(old('body', $post->body)) }}</textarea>
        @foreach(errors()['body'] ?? [] as $message)
            <p class="error">{{ e($message) }}</p>
        @endforeach
    </div>

    @if(!empty($post->image_path))
        <p>Current image: <img src="/{{ e($post->image_path) }}" alt="" width="200"></p>
    @endif

    <button type="submit">Save</button>
</form>

<p><a href="/posts">Back to posts</a></p>
