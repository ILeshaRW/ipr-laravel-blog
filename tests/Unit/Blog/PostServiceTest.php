<?php

namespace Tests\Unit\Blog;

use App\Http\Requests\Blog\Post\CreatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Repositories\Blog\PostRepository;
use App\Services\Blog\PostService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\Exception;
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
     * @throws Exception
     */
    public function test_create_active_post()
    {
        $request = $this->getRequest();

        $post = $request->validated();
        $post['user_id'] = $request->user()->id;

        $repository = $this->createMock(PostRepository::class);
        $repository->expects($this->once())
            ->method('getPostsByUserIdPaginated')
            ->with($request->user()->id, 1, true)
            ->willReturn(new LengthAwarePaginator(['t'], 1, 1));

        $repository->expects($this->once())
            ->method('create')
            ->with($post)
            ->willReturn(new Post($post));

        $service = new PostService($repository);
        $service->create($request->user()->id, $request->validated());
    }

    /**
     * Проверка на создание не активного поста.
     *
     * @return void
     * @throws Exception
     */
    public function test_create_not_active_post()
    {
        $request = $this->getRequest();

        $post = $request->validated();
        $post['user_id'] = $request->user()->id;

        $repository = $this->createMock(PostRepository::class);
        $repository->expects($this->once())
            ->method('getPostsByUserIdPaginated')
            ->with($request->user()->id, 1, true)
            ->willReturn(new LengthAwarePaginator([], 0, 1));

        $repository->expects($this->once())
            ->method('create')
            ->with(array_merge($post, ['active' => false]))
            ->willReturn(new Post($post));

        $service = new PostService($repository);
        $service->create($request->user()->id, $post);
    }

    /**
     * получение неактивных постов гостем, проверка получения исключения
     *
     * @return void
     */
    public function test_getPost_not_active_exception_guest()
    {
        $post = new Post(['title' => 't', 'text' => 't', 'preview_text' => 't', 'user_id' => 1, 'active' => false]);
        $repository = \Mockery::mock(PostRepository::class, function  (MockInterface $mock) use ($post){
            $mock->shouldReceive('getPost')
                ->withArgs([1])
                ->andReturn($post);
        });

        $postService = new PostService($repository);
        $this->expectException(ModelNotFoundException::class);
        $postService->getPost(1);
    }

    /**
     * Получение неактивных постов пользователем, проверка получения исключения.
     *
     * @return void
     */
    public function test_getPost_not_active_exception_auth()
    {
        $post = new Post(['title' => 't', 'text' => 't', 'preview_text' => 't', 'user_id' => 1, 'active' => false]);
        $repository = $this->getMockBuilder(PostRepository::class)->getMock();
        $repository->expects($this->once())
            ->method('getPost')
            ->with(1)
            ->willReturn($post);

        $postService = new PostService($repository);
        $this->expectException(ModelNotFoundException::class);
        $postService->getPost(1, 2);
    }

    /**
     * получение активного поста пользователем
     *
     * @return void
     * @throws Exception
     */
    public function test_getPost_active_auth()
    {
        $post = new Post(['title' => 't', 'text' => 't', 'preview_text' => 't', 'user_id' => 1, 'active' => true]);
        $repository = $this->createMock(PostRepository::class);

        $repository->expects($this->once())
            ->method('getPost')
            ->with(1)
            ->willReturn($post);

        $postService = new PostService($repository);
        $postService->getPost(1, 2);
    }

    /**
     * получение активного поста гостем
     *
     * @return void
     * @throws Exception
     */
    public function test_getPost_active_guest()
    {
        $post = new Post(['title' => 't', 'text' => 't', 'preview_text' => 't', 'user_id' => 1, 'active' => true]);
        $repository = $this->createMock(PostRepository::class);

        $repository->expects($this->once())
            ->method('getPost')
            ->with(1)
            ->willReturn($post);

        $postService = new PostService($repository);
        $postService->getPost(1);
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
