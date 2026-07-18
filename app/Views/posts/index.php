@extends('layouts/main')

@section('title')
My posts
@endsection

<h1>My posts</h1>

<p><a href="/posts/create">New post</a></p>

@if(empty($posts))
    <p>You have no posts yet.</p>
@else
    <ul class="posts">
    @foreach($posts as $post)
        <li>
            <h2>{{ e($post->title) }}</h2>
            <p>{{ e($post->body) }}</p>
            @if(!empty($post->image_path))
                <img src="/{{ e($post->image_path) }}" alt="{{ e($post->title) }}" width="200">
            @endif
            <p>
                <a href="/posts/{{ $post->id }}/edit">Edit</a>
                <form method="post" action="/posts/{{ $post->id }}" class="inline">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit">Delete</button>
                </form>
            </p>
        </li>
    @endforeach
    </ul>
@endif
