<?php

declare(strict_types=1);

namespace App\Controllers;

use Antimonial\Controller\Controller;
use Antimonial\Http\Request;
use Antimonial\Http\Response;
use Antimonial\Http\UploadedFile;
use Antimonial\Security\Auth;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = (new Post())
            ->where('user_id', Auth::id())
            ->query()
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->view('posts/index', ['posts' => $posts], 'layouts/main');
    }

    public function create(Request $request): Response
    {
        return $this->view('posts/create', [], 'layouts/main');
    }

    public function store(Request $request): Response
    {
        $data = $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'image' => 'nullable|image|max_size:2048',
        ]);

        $imagePath = null;

        /** @var UploadedFile|null $image */
        $image = $request->file('image');
        if ($image !== null && $image->isValid()) {
            $directory = ROOT_PATH . '/app/storage/uploads/posts';
            $name = bin2hex(random_bytes(16)) . '.' . $image->clientExtension();
            $image->store($directory, $name);
            $imagePath = 'app/storage/uploads/posts/' . $name;
        }

        (new Post())->insert([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'],
            'image_path' => $imagePath,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->redirect('/posts');
    }

    public function edit(Request $request, int $id): Response
    {
        $post = $this->findOwnedPost($id);

        if ($post === null) {
            return $this->notFound();
        }

        return $this->view('posts/edit', ['post' => $post], 'layouts/main');
    }

    public function update(Request $request, int $id): Response
    {
        $post = $this->findOwnedPost($id);

        if ($post === null) {
            return $this->notFound();
        }

        $data = $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);

        (new Post())->update($id, [
            'title' => $data['title'],
            'body' => $data['body'],
        ]);

        return $this->redirect('/posts');
    }

    public function destroy(Request $request, int $id): Response
    {
        $post = $this->findOwnedPost($id);

        if ($post === null) {
            return $this->notFound();
        }

        (new Post())->delete($id);

        return $this->redirect('/posts');
    }

    /**
     * Fetch a post by id, but only if it belongs to the current user.
     *
     * Returns null when the post does not exist OR is owned by someone
     * else — the caller responds with 404 in both cases so the app never
     * leaks whether a given id exists for another user.
     */
    private function findOwnedPost(int $id): ?object
    {
        $post = (new Post())->find($id);

        if ($post === null) {
            return null;
        }

        if ((int) $post->user_id !== Auth::id()) {
            return null;
        }

        return $post;
    }

    private function notFound(): Response
    {
        return (new Response)->status(404)->body('Not Found');
    }
}
