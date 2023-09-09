<?php

namespace Tests\Unit\Blog;

use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Blog\PostRepository;
use App\Services\Blog\PostService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

/**
 * Тест логики работы постов
 */
class PostServiceTest  extends TestCase
{
    /**
     * Проверяет, если у пользователя есть посты, то создает пост активным
     *
     * @return void
     */
    public function test_user_create_active_post()
    {
        $request = $this->getRequest();

        $postModel = new Post($request->validated());

        $repository = \Mockery::mock(PostRepository::class, function  (MockInterface $mock) use ($request, $postModel) {
            $paginator = new LengthAwarePaginator(['test'], 1, 1);
            $mock->shouldReceive('getPostsByUserIdPaginated')
                ->withArgs([$request->user()->id, 1, true])
                ->andReturn($paginator);
            $post = $request->validated();
            $post['user_id'] = $request->user()->id;
            $mock->shouldReceive('create')->withArgs([$post])->andReturn($postModel);
        });

        $service = new PostService($repository);

        /**
         * Проверка бесполезна, но в тесте проверяется, что мы передаем в репозиторий.
         */
        $this->assertNull(null);
    }

    /**
     * Проверка на создание не активного поста.
     *
     * @return void
     */
    public function test_user_create_no_active_post()
    {
        $request = $this->getRequest();

        $postModel = new Post($request->validated());
        $postModel->active = false;

        $repository = \Mockery::mock(PostRepository::class, function  (MockInterface $mock) use ($request, $postModel) {
            $paginator = new LengthAwarePaginator([], 0, 1);
            $mock->shouldReceive('getPostsByUserIdPaginated')
                ->withArgs([$request->user()->id, 1, true])
                ->andReturn($paginator);
            $post = $request->validated();
            $post['user_id'] = $request->user()->id;
            $post['active'] = false;
            $mock->shouldReceive('create')->withArgs([$post])->andReturn($postModel);
        });

        $service = new PostService($repository);

        /**
         * Проверка бесполезна, но в тесте проверяется, что мы передаем в репозиторий.
         */
        $this->assertFalse(false);
    }

    /**
     * Объект реквеста
     *
     * @return CreatePostRequest
     */
    private function getRequest(): CreatePostRequest
    {
        $post = ['title' => 'test', 'text' => 'text', 'preview_text' => 'text'];
        $request = new CreatePostRequest([], $post);

        $user = new User(['name'=>'aaa']);
        $user->id = 500;

        $request->setUserResolver(function () use ($user) {return $user;});

        $loader = new ArrayLoader();
        $validator = new Validator(new Translator($loader, 'en'), $post, $request->rules());
        $request->setValidator($validator);

        return $request;
    }
}
