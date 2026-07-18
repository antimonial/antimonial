<?php

declare(strict_types=1);

namespace Tests\Feature;

use Antimonial\Http\Request;
use Antimonial\Http\Response;
use Antimonial\Http\UploadedFile;
use Antimonial\Security\Auth;
use Antimonial\Session\Session;
use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class PostsTest extends TestCase
{
    private int $userA;
    private int $userB;

    protected function setUp(): void
    {
        Auth::logout();
        Session::forget('errors');
        Session::forget('old');

        $_SERVER = ['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/'];
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_FILES = [];

        // The test database is shared across the suite, so reset rows
        // between tests to keep them isolated.
        $db = \Antimonial\Database\DB::connection();
        $db->execute('DELETE FROM posts');
        $db->execute('DELETE FROM users');
        $db->execute("DELETE FROM sqlite_sequence WHERE name IN ('posts', 'users')");

        // Create two users up front.
        $this->userA = (int) (new User())->insert([
            'email' => 'a@example.com',
            'password' => password_hash('secret123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $this->userB = (int) (new User())->insert([
            'email' => 'b@example.com',
            'password' => password_hash('secret123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function loginAs(int $userId): void
    {
        Auth::login((new User())->find($userId));
    }

    private function post(array $data): Request
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $data;

        return Request::fromGlobals();
    }

    public function test_user_can_create_post(): void
    {
        $this->loginAs($this->userA);

        (new PostController())->store($this->post([
            'title' => 'My first post',
            'body' => 'Hello world',
        ]));

        $post = (new Post())->query()->where('user_id', $this->userA)->first();
        self::assertNotNull($post);
        self::assertSame('My first post', $post->title);
        self::assertNull($post->image_path);
    }

    public function test_other_user_cannot_edit_or_delete_post(): void
    {
        $this->loginAs($this->userA);
        (new PostController())->store($this->post([
            'title' => 'Owned post',
            'body' => 'Body',
        ]));

        $post = (new Post())->query()->where('user_id', $this->userA)->first();

        // Switch to user B and attempt to edit / delete user A's post.
        $this->loginAs($this->userB);
        $controller = new PostController();

        $edit = $controller->edit(Request::fromGlobals(), (int) $post->id);
        self::assertSame(404, $edit->getStatusCode());

        $delete = $controller->destroy(Request::fromGlobals(), (int) $post->id);
        self::assertSame(404, $delete->getStatusCode());

        // The post must still exist.
        self::assertNotNull((new Post())->find($post->id));
    }

    public function test_store_validates_required_fields(): void
    {
        $this->loginAs($this->userA);

        // Missing title and body -> ValidationException carrying the errors.
        $this->expectException(\Antimonial\Http\ValidationException::class);

        try {
            (new PostController())->store(Request::fromGlobals());
        } catch (\Antimonial\Http\ValidationException $e) {
            $errors = $e->errors();
            self::assertArrayHasKey('title', $errors);
            self::assertArrayHasKey('body', $errors);
            throw $e;
        }
    }

    public function test_uploaded_file_metadata_is_derived_from_disk(): void
    {
        // UploadedFile is final and requires a real HTTP upload for isValid()/
        // store(), so we only assert the metadata helpers that read the temp
        // file directly. This exercises the same code path used by the upload
        // handling in PostController::store().
        $tmp = tempnam(sys_get_temp_dir(), 'img') . '.png';
        file_put_contents($tmp, base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+M8AAAMBAQDJ/pLvAAAAAElFTkSuQmCC'
        ));

        $file = new UploadedFile([
            'name' => 'pixel.png',
            'type' => 'image/png',
            'tmp_name' => $tmp,
            'error' => UPLOAD_ERR_OK,
            'size' => filesize($tmp),
        ]);

        self::assertSame('png', $file->clientExtension());
        self::assertSame('image/png', $file->mimeType());
        self::assertSame('pixel.png', $file->clientName());
    }
}
