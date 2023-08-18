<?php

namespace Feature\Blog;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тест функционала комментариев
 */
class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Успешное создание комментария
     *
     * @return void
     * @throws \JsonException
     */
    public function test_create_comment_successful(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->create();

        $response = $this->put('post/comment', ['post_id' => $post->id, 'comment' => 'тут коммент']);
        $response->assertRedirectToRoute('post', [$post])->assertSessionHasNoErrors();
        $this->assertTrue($post->comments()->count() === 1);
    }

    /**
     * Создание комментария не авторизованным пользователем
     *
     * @return void
     */
    public function test_create_comment_auth_fail(): void
    {
        User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->put('post/comment', ['post_id' => $post->id, 'comment' => 'тут коммент']);
        $response->assertRedirectToRoute('login');
        $this->assertTrue($post->comments()->count() === 0);
    }

    /**
     * Создание комментария с некорректными значениями
     *
     * @return void
     */
    public function test_create_comment_validation_fail(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->create();

        $response = $this->put('post/comment', ['post_id' => $post->id, 'comment' => '']);
        $response->assertSessionHasErrors(['comment']);
        $this->assertTrue($post->comments()->count() === 0);
    }

    /**
     * Обновление комментария создателя поста, другим пользователем
     *
     * @return void
     */
    public function test_update_comment_another_user_fail(): void
    {
        $userCreator = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(
            [
                'user_id' => $userCreator->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );
        $userUpd = User::factory()->create();
        $this->actingAs($userUpd);
        $newText = 'тут коммент';


        $response = $this->post('post/comment/' . $comment->id, ['commentText' => $newText]);

        $response->assertStatus(403);
        $this->assertFalse($comment->refresh()->comment === $newText);
    }

    /**
     * Успешное обновление комментария владельцем комментария
     *
     * @return void
     * @throws \JsonException
     */
    public function test_update_comment_successful(): void
    {
        $userCreator = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(
            [
                'user_id' => $userCreator->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );

        $this->actingAs($userCreator);
        $newText = 'тут коммент';


        $response = $this->post('post/comment/' . $comment->id, ['commentText' => $newText]);
        $response->assertRedirectToRoute('post', [$post])->assertSessionHasNoErrors();
        $this->assertTrue($comment->refresh()->comment === $newText);
    }

    /**
     * Удаление комментария владельцем
     *
     * @return void
     * @throws \JsonException
     */
    public function test_delete_comment_successful(): void
    {
        $userCreator = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(
            [
                'user_id' => $userCreator->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );

        $this->actingAs($userCreator);
        $response = $this->delete('post/comment/' . $comment->id);
        $response->assertRedirectToRoute('post', [$post])->assertSessionHasNoErrors();
        $this->assertModelMissing($comment);
    }

    /**
     * Удаление комментария неавторизованным пользователем
     *
     * @return void
     * @throws \JsonException
     */
    public function test_delete_comment_auth_fail(): void
    {
        $userCreator = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(
            [
                'user_id' => $userCreator->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );

        $response = $this->delete('post/comment/' . $comment->id);
        $response->assertRedirectToRoute('login')->assertSessionHasNoErrors();
        $this->assertModelExists($comment);
    }

    /**
     * Попытка удаления комментария владельца поста посторонним пользователем
     *
     * @return void
     */
    public function test_delete_comment_another_user_fail(): void
    {
        $userCreator = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(
            [
                'user_id' => $userCreator->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );
        $userDel = User::factory()->create();

        $this->actingAs($userDel);
        $response = $this->delete('post/comment/' . $comment->id);
        $response->assertStatus(403);
        $this->assertModelExists($comment);
    }

    /**
     * Успешное удаление комментария постороннего пользователя владельцем поста.
     *
     * @return void
     * @throws \JsonException
     */
    public function test_delete_comment_post_creator_user_successful(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $comment = Comment::factory()->create(
            [
                'user_id' => User::factory()->create()->id,
                'comment' => 'текст коммента',
                'post_id' => $post->id,
            ]
        );

        $this->actingAs($user);
        $response = $this->delete('post/comment/' . $comment->id);
        $response->assertRedirectToRoute('post', [$post])->assertSessionHasNoErrors();
        $this->assertModelMissing($comment);
    }
}
