<div>
    <h2 class="tm-color-primary tm-post-title">Комментарии</h2>
    <hr class="tm-hr-primary tm-mb-45">

    @foreach($comments as $comment)
        <div class="tm-comment tm-mb-45">
            <figure class="tm-comment-figure">
                <figcaption class="tm-color-primary text-center">{{ $comment->user->name }}</figcaption>
            </figure>
            <div>
                <p>
                    {{ $comment->comment }}
                </p>
                <div class="d-flex justify-content-between">
                    <span class="tm-color-primary">{{ $comment->created_at }}</span>
                </div>
                @can('update', $comment)
                    <div data-edit-comment-id="{{ $comment->id }}">Редактировать</div>
                    <form
                        action="{{ route('update_comment', ['comment' => $comment->id]) }}"
                        method="POST"
                        style="display: none"
                        id="edit_comment_{{ $comment->id }}"
                    >
                        @csrf
                        <h2 class="tm-color-primary tm-post-title mb-4">Редактировать комментарий</h2>
                        <div class="mb-4">
                            <textarea class="form-control" name="commentText" rows="8">{{ $comment->comment }}</textarea>
                        </div>
                        <div class="text-right">
                            <button class="tm-btn tm-btn-primary tm-btn-small">Отправить</button>
                        </div>

                    </form>
                @endcan
                @can('delete', $comment)
                    <form
                        action="{{ route('delete_comment', ['comment' => $comment->id]) }}"
                        method="POST"
                    >
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="Удалить">
                    </form>
                @endcan
            </div>
        </div>
    @endforeach

    @auth
        <form action="{{ route('create_comment') }}" method="POST" class="mb-5 tm-comment-form">
            @method('PUT')
            @csrf
            <h2 class="tm-color-primary tm-post-title mb-4">Ваш комментарий</h2>
            <div class="mb-4">
                <textarea class="form-control" name="comment" rows="8"></textarea>
            </div>
            <div class="text-right">
                <button class="tm-btn tm-btn-primary tm-btn-small">Отправить</button>
            </div>
            <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
        </form>
    @endauth
</div>
@once
    @push('scripts')
        <script src="{{ asset("js/comment.js") }}"></script>
    @endpush
@endonce
