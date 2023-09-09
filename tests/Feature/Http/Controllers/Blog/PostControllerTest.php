<?php

namespace Tests\Feature\Http\Controllers\Blog;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест листинга постов
     */
    public function test_the_post_list_successful_response(): void
    {
        $response = $this->get('posts');

        $response->assertStatus(200);
    }

    /**
     * Тест детальной поста
     */
    public function test_post_detail_successful_response(): void
    {
        User::withoutEvents(fn() => User::factory()->create());
        Post::withoutEvents(fn() => Post::factory()->create());

        $response = $this->get('post/1');

        $response->assertStatus(200);
    }

    /**
     * Тест успешного создания поста
     *
     * @return void
     */
    public function test_create_success(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('post/create', ['title' => 'test', 'preview_text' => 'test', 'text' => 'testtesttest']);

        $this->assertDatabaseCount(Post::class, 1);
        $post = Post::first();
        Notification::assertSentTo([$user], PostCreated::class);

        $response->assertRedirectToRoute('edit_post_page', [$post]);
    }

    /**
     * Тест не успешного создания поста
     *
     * @return void
     * @throws \Exception
     */
    public function test_create_fail(): void
    {
        Notification::fake();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('post/create', ['title' => '', 'preview_text' => '', 'text' => '']);
        $this->assertDatabaseCount(Post::class, 0);
        $response->assertSessionHasErrors(['title', 'preview_text', 'text']);

        Notification::assertNotSentTo([$user], PostCreated::class);
    }

    /**
     * Тест успешного обновления поста
     */
    public function test_update_success(): void
    {
        $postArray = ['title' => 'test', 'preview_text' => 'test', 'text' => 'testtesttest'];
        $user = User::withoutEvents(fn() => User::factory()->create());
        $postArray['user_id'] = $user->id;
        $post = Post::withoutEvents(function () use ($postArray) { return Post::factory()->create($postArray); });
        $this->actingAs($user);
        $postArray['title'] = 'replace_title';

        $response = $this->post('post/edit/' . $post->id, $postArray);

        $this->assertTrue($post->refresh()->title === $postArray['title']);
        $response->assertRedirectToRoute('edit_post_page', [$post])
            ->assertSessionHasNoErrors();
    }

    /**
     * тест не успешного обновления поста
     *
     * @return void
     */
    public function test_update_fail(): void
    {
        $user = User::withoutEvents(fn() => User::factory()->create());
        $post = Post::withoutEvents(fn() => Post::factory()->create());
        $this->actingAs($user);
        $postArray = ['title' => 'test', 'preview_text' => '', 'text' => 'testtesttest'];

        $response = $this->post('post/edit/' . $post->id, $postArray);
        $this->assertFalse($post->refresh()->preview_text === $postArray['preview_text']);

        $response->assertSessionHasErrors(['preview_text']);
    }

    /**
     * Тест страницы обновления поста не авторизованным пользователем
     */
    public function test_update_auth_fail(): void
    {
        User::withoutEvents(fn() => User::factory()->create());
        $post = Post::withoutEvents(fn() => Post::factory()->create());
        $postArray = ['title' => $post->title . '_upd', 'preview_text' => 'test', 'text' => 'testtesttest'];

        $this->post('post/edit/' . $post->id, $postArray)
            ->assertRedirectToRoute('login');

        $this->assertFalse($post->refresh()->title === $postArray['title']);
    }

    /**
     * Тест страницы обновления поста не авторизованным пользователем
     */
    public function test_create_auth_fail(): void
    {
        Notification::fake();
        $user = User::withoutEvents(fn() => User::factory()->create());
        $postArray = ['title' => 'test', 'preview_text' => 'test', 'text' => 'testtesttest'];

        $this->put('post/create', $postArray)
            ->assertRedirectToRoute('login');
        $this->assertDatabaseCount(Post::class, 0);

        Notification::assertNotSentTo([$user], PostCreated::class);
    }

    /**
     * Тест страницы создания поста авторизованным
     *
     * @return void
     */
    public function test_create_successful_response(): void
    {
        $user = User::withoutEvents(fn() => User::factory()->create());
        $this->actingAs($user);

        $this->get('post/create')
            ->assertStatus(200);
    }

    /**
     * Тест страницы создания поста не авторизованным пользователем
     *
     * @return void
     */
    public function test_create_fail_access_response(): void
    {
        $this->get('post/create')
            ->assertRedirectToRoute('login');
    }

    /**
     * Тест страницы редактирования поста авторизованным
     *
     * @return void
     */
    public function test_edit_successful_response(): void
    {
        $user = User::withoutEvents(fn() => User::factory()->create());
        $this->actingAs($user);

        $this->get('post/edit/' . Post::withoutEvents(fn () => Post::factory()->create())->id)
            ->assertStatus(200);
    }

    /**
     * Тест страницы редактирования поста не авторизованным пользователем
     *
     * @return void
     */
    public function test_edit_fail_access_response(): void
    {
        User::withoutEvents(fn() => User::factory()->create());

        $this->get('post/edit/' . Post::withoutEvents(fn() => Post::factory()->create())->id)
            ->assertRedirectToRoute('login');
    }

    /**
     * Успешное удаление поста
     *
     * @return void
     * @throws \JsonException
     */
    public function test_delete_successful(): void
    {
        $user = User::withoutEvents(fn() => User::factory()->create());
        $post = Post::withoutEvents(fn() => Post::factory()->create());
        $this->actingAs($user);

        $this->delete('post/edit/' . $post->id)
            ->assertRedirectToRoute('posts')
            ->assertSessionHasNoErrors();
        $this->assertModelMissing($post);
    }

    /**
     * Ошибка удаления поста для неавторизованного пользователя
     *
     * @return void
     * @throws \JsonException
     */
    public function test_delete_auth_fail(): void
    {
        User::withoutEvents(fn() => User::factory()->create());
        $post = Post::withoutEvents(fn() => Post::factory()->create());

        $this->delete('post/edit/' . $post->id)
            ->assertRedirectToRoute('login')
            ->assertSessionHasNoErrors();

        $this->assertModelExists($post);
    }

    /**
     * Ошибка при удалении чужого поста
     *
     * @return void
     */
    public function test_delete_another_user_fail(): void
    {
        User::withoutEvents(fn() => User::factory()->create());
        $post = Post::withoutEvents(fn() => Post::factory()->create());
        $newUser = User::withoutEvents(fn() => User::factory()->create());
        $this->actingAs($newUser);

        $this->delete('post/edit/' . $post->id)
            ->assertStatus(403);
        $this->assertModelExists($post);
    }
}
